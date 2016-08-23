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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Traits\CriteriaAwareTrait;

/**
 * QueryBuilder
 * @method SearchCriteria getCriteria()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryBuilder implements CriteriaAwareInterface
{

	use CriteriaAwareTrait;

	/**
	 * Manganel instance
	 * @var Manganel
	 */
	private $manganel = null;

	/**
	 * Annotated model
	 * @var AnnotatedInterface[]
	 */
	private $models = [];

	public function __construct($model = null)
	{
		if (!empty($model))
		{
			$this->models[] = $model;
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

	public function add($model)
	{
		if (is_array($model))
		{
			foreach ($model as $m)
			{
				$this->models[] = $m;
			}
			return;
		}
		$this->models[] = $model;
	}

	/**
	 *
	 * @param string $q
	 * @return int
	 */
	public function count($q = null)
	{
		$params = $this->getParams($q);
		$result = $this->manganel->getClient()->count($params);
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
		$result = $this->manganel->getClient()->search($params);
		if (empty($result) && empty($result['hits']) && empty($result['hits']['hits']))
		{
			return []; // @codeCoverageIgnore
		}
		return $result['hits']['hits'];
	}

	private function getParams($q = null)
	{
		$body = [];
		// Try to get query from criteria if empty
		$criteria = $this->getCriteria();
		if (null === $q && !empty($criteria))
		{
			$q = $criteria->getSearch();
		}

		if (null === $q)
		{
			// Match all documents if query is null
			$query = [
				'match_all' => []
			];
		}
		else
		{
			// Use query string matching
			$query = [
				'simple_query_string' => [
					'query' => $q
				]
			];
		}

//
//		  TODO: Build somewhat similar query, if criteria has
//		  ANY conditions. Add conditions to `filter` clause:
//		  {
//				"query": {
//					"filtered": {
//						"query": {
//							"query_string": {
//								"query": "jkow OR features"
//							}
//						},
//						"filter": {
//							"term": {
//								"published.en": true
//							}
//						}
//					}
//				}
//			}
//


		$body['query'] = $query;

		if (!empty($criteria))
		{
			if ($criteria->getLimit() || $criteria->getOffset())
			{
				$body['from'] = $criteria->getOffset();
				$body['size'] = $criteria->getLimit();
			}
		}

		if (empty($this->models))
		{
			$type = '_all';
		}
		else
		{
			$types = [];
			foreach ($this->models as $model)
			{
				if (!$model instanceof AnnotatedInterface)
				{
					throw new \UnexpectedValueException(sprintf('Expected `%s` instance, got `%s`', AnnotatedInterface::class, is_object($model) ? get_class($model) : gettype($model)));
				}
				$types[] = CollectionNamer::nameCollection($model);
			}
			$type = implode(',', array_unique($types));
		}

		$params = [
			'index' => strtolower($this->manganel->index),
			'type' => $type,
			'body' => $body
		];
		return $params;
	}

}
