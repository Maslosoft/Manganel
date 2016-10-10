<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use Closure;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Manganel\Exceptions\ManganelException;
use Maslosoft\Manganel\Meta\ManganelMeta;
use MongoId;
use UnexpectedValueException;

/**
 * IndexMangager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexManager
{

	/**
	 * Manganel instance
	 * @var Manganel
	 */
	private $manganel = null;

	/**
	 * Model meta data
	 * @var ManganelMeta
	 */
	private $meta = null;

	/**
	 * Annotated model
	 * @var AnnotatedInterface
	 */
	private $model;

	/**
	 * Whether model is indexable
	 * @var bool
	 */
	private $isIndexable = false;

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

	public function index()
	{
		if (!$this->isIndexable)
		{
			return;
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

		// In some cases $value *might* still be mongoId type,
		// see https://github.com/Maslosoft/Addendum/issues/43
		$func = function($value)
		{
			if ($value instanceof MongoId)
			{
				return (string) $value;
			}
			return $value;
		};
		$filtered = filter_var($body, \FILTER_CALLBACK, ['options' => $func]);

		// Create proper elastic search request array
		$params = [
			'body' => $filtered,
		];
		try
		{
			$this->getClient()->index($this->getParams($params));
		}
		catch (BadRequest400Exception $e)
		{
			// Throw previous exception,
			// as it holds more meaningfull information
			$previous = $e->getPrevious();
			$message = sprintf('Exception while indexing `%s`@`%s`: %s', get_class($this->model), $this->manganel->indexId, $previous->getMessage());
			throw new BadRequest400Exception($message);
		}
	}

	public function delete()
	{
		if (!$this->isIndexable)
		{
			return;
		}
		$this->getClient()->delete($this->getParams());
	}

	public function get($id = null)
	{
		if (!$this->isIndexable)
		{
			return;
		}
		$params = $id ? ['id' => (string) $id] : [];
		$data = $this->getClient()->get($this->getParams($params))['_source'];
		return SearchArray::toModel($data);
	}

	/**
	 * Get client
	 * @return Client
	 */
	public function getClient()
	{
		return $this->manganel->getClient();
	}

	private function getParams($params = [])
	{
		// Check refresh option
		if ($this->manganel->refresh instanceof Closure)
		{
			$func = $this->manganel->refresh;
			$refresh = (bool) $func($this->model);
		}
		else
		{
			$refresh = $this->manganel->refresh;
		}
		$result = [
			'index' => strtolower($this->manganel->index),
			'type' => CollectionNamer::nameCollection($this->model),
			'id' => (string) $this->model->_id,
			'refresh' => $refresh
		];
		return array_merge($result, $params);
	}

}
