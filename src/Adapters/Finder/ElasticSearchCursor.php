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

use Maslosoft\Mangan\Interfaces\Adapters\FinderCursorInterface;
use Maslosoft\Manganel\Decorators\IndexDecorator;
use Maslosoft\Manganel\Decorators\MaxScoreDecorator;
use Maslosoft\Manganel\Decorators\ScoreDecorator;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;

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
		$criteria = $qb->getCriteria();
		if (empty($criteria))
		{
			$this->qb->setCriteria(new SearchCriteria());
		}
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
			$results = [];
			$data = $this->qb->search(null, $results);

			// Something went wrong
			if(empty($results['hits']))
			{
				$this->data = [];
				return;
			}
			$maxScore = $results['hits']['max_score'];
			foreach ($data as $result)
			{
				$document = $result['_source'];
				/**
				 * TODO Maybe refactor it into plugable interface, with params:
				 * $document,
				 * $result,
				 * $results
				 */
				$document[IndexDecorator::Key] = $result['_index'];
				$document[ScoreDecorator::Key] = $result['_score'];
				$document[MaxScoreDecorator::Key] = $maxScore;

				$this->data[] = $document;
			}
		}
	}

// </editor-fold>
}
