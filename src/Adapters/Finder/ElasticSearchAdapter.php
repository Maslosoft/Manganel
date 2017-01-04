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
		return new ElasticSearchCursor($this->qb);
	}

	public function findOne(CriteriaInterface $criteria, $fields = array())
	{

	}

}
