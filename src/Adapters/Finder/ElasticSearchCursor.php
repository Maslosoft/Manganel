<?php

namespace Maslosoft\Manganel\Adapters\Finder;

use Maslosoft\Mangan\Interfaces\Adapters\FinderCursorInterface;
use Maslosoft\Manganel\QueryBuilder;

/**
 * ElasticSearchCursor
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ElasticSearchCursor implements FinderCursorInterface
{

	/**
	 * Whether query was sent to search server
	 * @var bool
	 */
	private $isExecuted = false;

	/**
	 *
	 * @var QueryBuilder
	 */
	private $qb = null;

	/**
	 *
	 * @var array
	 */
	private $data = [];

	public function __construct(QueryBuilder $qb)
	{
		$this->qb = $qb;
	}

	public function limit($num)
	{
		$this->qb->getCriteria()->limit($num);
		return $this;
	}

	public function skip($num)
	{
		$this->qb->getCriteria()->offset($num);
		return $this;
	}

	public function sort(array $fields)
	{
		$this->qb->getCriteria()->setSort($fields);
		return $this;
	}

	public function fields(array $fields)
	{
		$this->qb->getCriteria()->select($fields);
		return $this;
	}

// <editor-fold defaultstate="collapsed" desc="Countable impl">

	/**
	 *
	 * @return int
	 */
	public function count()
	{
		$this->execute();
		return count($this->data);
	}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Iterator impl">
	public function current()
	{
		$this->execute();
		return current($this->data);
	}

	public function key()
	{
		$this->execute();
		return key($this->data);
	}

	public function next()
	{
		$this->execute();
		next($this->data);
	}

	public function rewind()
	{
		$this->execute();
		return reset($this->data);
	}

	public function valid()
	{
		$this->execute();
		$key = key($this->data);
		return ($key !== null && $key !== false);
	}

	private function execute()
	{
		if (!$this->isExecuted)
		{
			$this->isExecuted = true;
			$data = $this->qb->search();
			foreach ($data as $result)
			{
				$this->data[] = $result['_source'];
			}
		}
	}

// </editor-fold>
}
