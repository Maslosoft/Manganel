<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Traits\DataProvider\ConfigureTrait;
use Maslosoft\Mangan\Traits\DataProvider\CriteriaTrait;
use Maslosoft\Mangan\Traits\DataProvider\DataTrait;
use Maslosoft\Mangan\Traits\DataProvider\PaginationTrait;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use Maslosoft\Mangan\Traits\SortAwareTrait;

/**
 * SearchProvider
 *
 * @method SearchCriteria getCriteria()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchProvider implements DataProviderInterface
{

	use ConfigureTrait,
	  CriteriaTrait,
	  DataTrait,
	  ModelAwareTrait,
	  PaginationTrait,
	  SortAwareTrait;

	const CriteriaClass = SearchCriteria::class;

	/**
	 * Total items count cache
	 * @var int
	 */
	private $totalItemCount = null;

	public function __construct($modelClass, $config = [])
	{
		$this->configure($modelClass, $config);
	}

	protected function fetchData()
	{
		$criteria = $this->getCriteria();
		$qb = new QueryBuilder($this->getModel());
		$qb->setCriteria($criteria);
		$rawResults = $qb->search($criteria->getSearch());
		$results = [];
		foreach ($rawResults as $data)
		{
			$results[] = SearchArray::toModel($data);
		}
		return $results;
	}

	public function getItemCount($refresh = false)
	{
		return count($this->getData($refresh));
	}

	public function getTotalItemCount()
	{
		if ($this->totalItemCount === null)
		{
			$qb = new QueryBuilder($this->getModel());
			$this->totalItemCount = $qb->count($this->getCriteria()->getSearch());
		}
		return $this->totalItemCount;
	}

}
