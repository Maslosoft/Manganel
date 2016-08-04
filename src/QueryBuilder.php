<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
				'query_string' => [
					'query' => $q
				]
			];
		}
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
