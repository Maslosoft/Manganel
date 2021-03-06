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

namespace Maslosoft\Manganel\Adapters\Finder;

use function array_keys;
use Maslosoft\Mangan\Interfaces\Adapters\FinderAdapterInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;

/**
 * ElasticSearchAdapter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ElasticSearchAdapter implements FinderAdapterInterface
{

	/**
	 *
	 * @var QueryBuilder
	 */
	private $qb = null;
	private $models = [];

	public function __construct($models)
	{
		$this->qb = new QueryBuilder();
		$this->qb->setModels($models);
		$this->models = $models;
	}

	public function count(CriteriaInterface $criteria)
	{
		$this->ensure($criteria);
		return $this->qb->setCriteria($criteria)->count();
	}

	public function findMany(CriteriaInterface $criteria, $fields = array())
	{
		$this->prepare($criteria, $fields);
		return new ElasticSearchCursor($this->qb);
	}

	public function findOne(CriteriaInterface $criteria, $fields = array())
	{
		$this->prepare($criteria, $fields);
		$data = (new ElasticSearchCursor($this->qb))->current();
		if (false === $data)
		{
			return null;
		}
		return $data;
	}

	/**
	 *
	 * @internal Used for debugging purposes, should not be used for any manipulations!
	 * @return QueryBuilder
	 */
	public function getQueryBuilder()
	{
		return $this->qb;
	}

	private function prepare(CriteriaInterface $criteria, $fields)
	{
		$this->ensure($criteria);
		assert($criteria instanceof SearchCriteria);

		$this->qb->setCriteria($criteria);
		if (!empty($fields))
		{
			$selected = [];
			foreach ($fields as $index => $value)
			{
				$selected[$index] = true;
			}
			$this->qb->getCriteria()->select($selected);
		}
	}

	private function ensure(&$criteria)
	{
		if (!$criteria instanceof SearchCriteria)
		{
			$criteria = new SearchCriteria($criteria);
		}
		$criteria->setModels($this->models);
	}

}
