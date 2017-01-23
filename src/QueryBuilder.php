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

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Traits\CriteriaAwareTrait;
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
	 * @var Manganel
	 */
	private $manganel = null;

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
	public function add($model)
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
	public function setCriteria($criteria)
	{
		$this->criteria = $criteria;
		assert($criteria instanceof SearchCriteria);
		$this->add($criteria->getModels());
		return $this;
	}

	/**
	 *
	 * @param string $q
	 * @return int
	 */
	public function count($q = null)
	{
		$params = $this->getParams($q);
		try
		{
			$result = $this->manganel->getClient()->count($params);
		}
		catch (BadRequest400Exception $e)
		{
			// Throw previous exception,
			// as it holds more meaningfull information
			$json = json_encode($params, JSON_PRETTY_PRINT);
			$previous = $e->getPrevious();
			$message = sprintf("Exception (%s) while querying `%s`: \n%s\n", $previous->getMessage(), $this->manganel->indexId, $json);
			throw new BadRequest400Exception($message, 400, $e);
		}
		if (empty($result) && empty($result['count']))
		{
			return 0; // @codeCoverageIgnore
		}
		return $result['count'];
	}

	/**
	 * Get search results
	 * @param string $q
	 * @return array
	 */
	public function search($q = null)
	{
		$params = $this->getParams($q);

		try
		{
			$result = $this->manganel->getClient()->search($params);
		}
		catch (BadRequest400Exception $e)
		{
			// Throw previous exception,
			// as it holds more meaningfull information
			$json = json_encode($params, JSON_PRETTY_PRINT);
			$previous = $e->getPrevious();
			$message = sprintf("Exception (%s) while querying `%s`: \n%s\n", $previous->getMessage(), $this->manganel->indexId, $json);
			throw new BadRequest400Exception($message, 400, $e);
		}

		if (empty($result) && empty($result['hits']) && empty($result['hits']['hits']))
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
		if (empty($criteria))
		{
			$criteria = new SearchCriteria;
		}
		if (!empty($q))
		{
			$criteria->search($q);
		}

		$criteria->setModels($this->getModels());

		$decorator = new QueryBuilderDecorator($this->manganel);
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
			'type' => $type,
			'body' => $body
		];
//		return $params;
		return RecursiveFilter::mongoIdToString($params);
	}

}
