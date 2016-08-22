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

use Exception;
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
	 * @var AnnotatedInterface
	 */
	private $model;

	public function __construct($model)
	{
		$this->model = $model;
		$this->manganel = Manganel::create($this->model);
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

		$params = [
			'index' => strtolower($this->manganel->index),
			'type' => CollectionNamer::nameCollection($this->model),
			'body' => $body
		];
		return $params;
	}

}
