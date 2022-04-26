<?php

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package   maslosoft/manganel
 * @license   AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link      https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use Closure;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Manganel\Events\ErrorEvent;
use Maslosoft\Manganel\Exceptions\ManganelException;
use Maslosoft\Manganel\Helpers\ExceptionHandler;
use Maslosoft\Manganel\Helpers\RecursiveFilter;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\Meta\ManganelMeta;
use UnexpectedValueException;
use function array_key_exists;
use function array_replace_recursive;

/**
 * IndexManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexManager
{
	/**
	 * Alias to ErrorEvent::EventIndexingError
	 */
	public const EventIndexingError = ErrorEvent::EventIndexingError;

	public const DocType = '_doc';

	public const TypeField = '_type_';

	/**
	 * Manganel instance
	 * @var Manganel|null
	 */
	private ?Manganel $manganel = null;

	/**
	 * Model meta data
	 * @var ManganelMeta|null
	 */
	private ?ManganelMeta $meta = null;

	/**
	 * Annotated model
	 * @var AnnotatedInterface
	 */
	private AnnotatedInterface $model;

	/**
	 * Whether model is indexable
	 * @var bool
	 */
	private bool $isIndexable = false;

	public function __construct($model)
	{
		$this->model = $model;
		$this->meta = ManganelMeta::create($this->model);
		if (!empty($this->meta->type()->indexId) && false !== $this->meta->type()->indexId)
		{
			$this->isIndexable = true;
		}
		if ($this->isIndexable)
		{
			if (!isset($this->model->_id))
			{
				throw new ManganelException(sprintf('Property `_id` is not set in model `%s`, this is required by Manganel', get_class($this->model)));
			}
			$this->manganel = Manganel::create($this->model);
		}
	}

	/**
	 * Add or replace document in index
	 *
	 * @return bool
	 * @throws BadRequest400Exception
	 */
	public function index(): bool
	{
		if (!$this->isIndexable)
		{
			return false;
		}
		// NOTE: Transformer must ensure that _id is string, not MongoId
		$body = SearchArray::fromModel($this->model);
		if (array_key_exists('_id', $body))
		{
			$config = Mangan::fromModel($this->model)->sanitizersMap;
			if (!array_key_exists(SearchArray::class, $config))
			{
				throw new UnexpectedValueException(sprintf('Mangan is not properly configured for Manganel. Signals must be generated or add configuration manually from `%s::getDefault()`', ConfigManager::class));
			}
			else
			{
				throw new UnexpectedValueException(sprintf('Cannot index `%s`, as it contains _id field. Either use MongoObjectId sanitizer on it, or rename.', get_class($this->model)));
			}
		}

		// Create proper elastic search request array
		$params = [
			'body' => RecursiveFilter::mongoIdToString($body),
		];
		try
		{
			$fullParams = $this->getParams($params);
			// Need to check if exists, or update will fail
			$existsParams = [
				'index' => $fullParams['index'],
				'type' => self::DocType,
				'id' => $fullParams['id']
			];
			$exists = $this->getClient()->exists($existsParams);

			if (!$exists)
			{
				$result = $this->getClient()->index($fullParams);
			}
			else
			{
				$updateParams = $fullParams;
				$updateParams['body'] = [
					'doc' => $fullParams['body']
				];
				$result = $this->getClient()->update($updateParams);
			}
			if (array_key_exists('result', $result) && $result['result'] === 'updated')
			{
				// For ES 5
				return true;
			}
			elseif (is_array($result))
			{
				// For earlier ES
				return true;
			}
		} catch (BadRequest400Exception $e)
		{
			if (ExceptionHandler::handled($e, $this->model, self::EventIndexingError))
			{
				return false;
			}
			throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
		} catch (Exception $e)
		{
			if (ExceptionHandler::handled($e, $this->model, self::EventIndexingError))
			{
				return false;
			}
			throw $e;
		}
		return false;
	}

	/**
	 * Delete document from index
	 *
	 * @return bool
	 * @throws BadRequest400Exception
	 */
	public function delete(): bool
	{
		if (!$this->isIndexable)
		{
			return false;
		}
		try
		{
			$params = $this->getParams();
			$this->getClient()->delete($params);
			return true;
		} catch (BadRequest400Exception $e)
		{
			if (ExceptionHandler::handled($e, $this->model, self::EventIndexingError))
			{
				return false;
			}
			throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
		} catch (Exception $e)
		{
			if (ExceptionHandler::handled($e, $this->model, self::EventIndexingError))
			{
				return false;
			}
			throw $e;
		}
	}

	public function get($id = null)
	{
		if (!$this->isIndexable)
		{
			return null;
		}
		$params = $id ? ['id' => (string)$id] : [];
		$data = $this->getClient()->get($this->getParams($params))['_source'];
		return SearchArray::toModel($data);
	}

	/**
	 * Get client
	 * @return Client
	 */
	public function getClient(): Client
	{
		return $this->manganel->getClient();
	}

	private function getParams($params = []): array
	{
		// Check refresh option
		if ($this->manganel->refresh instanceof Closure)
		{
			$func = $this->manganel->refresh;
			$refresh = (bool)$func($this->model);
		}
		else
		{
			$refresh = $this->manganel->refresh;
		}
		$result = [
			'index' => strtolower($this->manganel->index),
			'type' => self::DocType,
			'id' => (string)$this->model->_id,
			'refresh' => $refresh,
		];

		// Add custom type field on inserts to body
		if (array_key_exists('body', $params))
		{
			$result['body'] = [
				self::TypeField => TypeNamer::nameType($this->model)
			];
		}
		return array_replace_recursive($result, $params);
	}

}
