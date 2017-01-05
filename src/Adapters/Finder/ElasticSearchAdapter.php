<?php

namespace Maslosoft\Manganel\Adapters\Finder;

use Maslosoft\Mangan\Interfaces\Adapters\FinderAdapterInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Manganel\QueryBuilder;

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

	public function __construct($models)
	{
		$this->qb = new QueryBuilder();
		$this->qb->setModels($models);
	}

	public function count(CriteriaInterface $criteria)
	{
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
		$data = (new ElasticSearchCursor(($this->qb)))->current();
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
		$this->qb->setCriteria($criteria);
		if (!empty($fields))
		{
			$selected = array_flip($fields);
			foreach ($selected as $index => $value)
			{
				$selected[$index] = true;
			}
			$this->qb->getCriteria()->select($fields);
		}
	}

}
