<?php

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Traits\CriteriaAwareTrait;
use Maslosoft\Manganel\Events\ErrorEvent;
use Maslosoft\Manganel\Helpers\ExceptionHandler;
use Maslosoft\Manganel\Helpers\QueryBuilderDecorator;
use Maslosoft\Manganel\Helpers\RecursiveFilter;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;
use UnexpectedValueException;

/**
 * QueryBuilder
 * @method SearchCriteria getCriteria()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryBuilder implements CriteriaAwareInterface
{

	use CriteriaAwareTrait,
	  UniqueModelsAwareTrait;

	/**
	 * Manganel instance
	 * @var Manganel|null
	 */
	private ?Manganel $manganel;

	public function __construct($model = null)
	{
		if (!empty($model))
		{
			$this->addModel($model);
		}
		if (!empty($model))
		{
			$this->manganel = Manganel::create($model);
		}
		else
		{
			$this->manganel = Manganel::fly();
		}
	}

	/**
	 * Add model or array of models
	 * @param AnnotatedInterface|AnnotatedInterface[] $model
	 * @return void
	 */
	public function add($model): void
	{
		if (is_array($model))
		{
			foreach ($model as $m)
			{
				$this->addModel($m);
			}
			return;
		}
		$this->addModel($model);
	}

	/**
	 * Set criteria
	 * @param CriteriaInterface|array $criteria
	 * @return static
	 */
	public function setCriteria($criteria): QueryBuilder
	{
		$this->criteria = $criteria;
		assert($criteria instanceof SearchCriteria);
		$this->add($criteria->getModels());
		return $this;
	}

	/**
	 * @param string|null $q
	 * @return int
	 * @throws BadRequest400Exception
	 */
	public function count(string $q = null): int
	{
		$params = $this->getParams($q);
		try
		{
			$result = $this->manganel->getClient()->count($params);
		}
		catch (Missing404Exception $e)
		{
			$params = ['index' => strtolower($this->manganel->index)];
			$this->manganel->getClient()->indices()->create($params);
		}
		catch (BadRequest400Exception $e)
		{
			if(!ExceptionHandler::handled($e, $this->models[0], ErrorEvent::EventBadRequest))
			{
				throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
			}
		}
		catch (NoNodesAvailableException $e)
		{
			if(!ExceptionHandler::handled($e, $this->models[0], ErrorEvent::EventNoNodes))
			{
				throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
			}
		}
		if (empty($result) || empty($result['count']))
		{
			return 0; // @codeCoverageIgnore
		}
		return $result['count'];
	}

	/**
	 * Get search results - hits from elasticsearch response.
	 * Optionally raw results might be obtained by reference via second param.
	 *
	 * @param string|null $q
	 * @param array       $result
	 * @return array
	 * @throws BadRequest400Exception
	 */
	public function search(string $q = null, array &$result = []): array
	{
		$params = $this->getParams($q);

		try
		{
			$result = $this->manganel->getClient()->search($params);
		}
		catch (Missing404Exception $e)
		{
			$params = ['index' => strtolower($this->manganel->index)];
			$this->manganel->getClient()->indices()->create($params);
		}
		catch (BadRequest400Exception $e)
		{
			if(!ExceptionHandler::handled($e, $this->models[0], ErrorEvent::EventBadRequest))
			{
				throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
			}
		}
		catch(NoNodesAvailableException $e)
		{
			if(!ExceptionHandler::handled($e, $this->models[0], ErrorEvent::EventNoNodes))
			{
				throw ExceptionHandler::getDecorated($this->manganel, $e, $params);
			}
		}

		if (empty($result) || empty($result['hits']) || empty($result['hits']['hits']))
		{
			return [];
		}
		return $result['hits']['hits'];
	}

	public function getParams($q = null)
	{
		$body = [];
		// Try to get query from criteria if empty
		$criteria = $this->getCriteria();
		if (!$criteria instanceof SearchCriteria && $criteria instanceof CriteriaInterface)
		{
			$criteria = new SearchCriteria($criteria);
		}
		if ($criteria === null)
		{
			$criteria = new SearchCriteria;
		}
		if (!empty($q))
		{
			$criteria->search($q);
		}

		$criteria->setModels($this->getModels());

		$decorator = new QueryBuilderDecorator($this->manganel);
		$decorator->setModels($this->getModels());
		$decorator->decorate($body, $criteria);
		$models = $this->getModels();
		if (empty($models))
		{
			$type = '_all';
		}
		else
		{
			$types = [];
			foreach ($models as $model)
			{
				assert($model instanceof AnnotatedInterface, new UnexpectedValueException(sprintf('Expected `%s` instance, got `%s`', AnnotatedInterface::class, is_object($model) ? get_class($model) : gettype($model))));
				$types[] = TypeNamer::nameType($model);
			}
			$type = implode(',', array_unique($types));
		}

		$params = [
			'index' => strtolower($this->manganel->index),
			'type' => IndexManager::DocType,
			'body' => $body
		];
//		return $params;
		return RecursiveFilter::mongoIdToString($params);
	}

}
