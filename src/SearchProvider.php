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

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Traits\DataProvider\ConfigureTrait;
use Maslosoft\Mangan\Traits\DataProvider\CriteriaTrait;
use Maslosoft\Mangan\Traits\DataProvider\DataTrait;
use Maslosoft\Mangan\Traits\DataProvider\PaginationTrait;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use Maslosoft\Mangan\Traits\SortAwareTrait;
use Maslosoft\Manganel\Interfaces\IndexAwareInterface;
use Maslosoft\Manganel\Interfaces\ScoreAwareInterface;

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

	/**
	 * Finder instance
	 * @var SearchFinder
	 */
	private $finder = null;
	private $isStopped = false;

	public function __construct($modelClass = null, $config = [])
	{
		$this->configure($modelClass, $config);
		$this->finder = new SearchFinder($this->getModel());
	}

	public function stop($doStop = true)
	{
		$this->isStopped = $doStop;
	}

	protected function fetchData()
	{
		if ($this->isStopped)
		{
			return [];
		}
		$criteria = $this->configureFetch();

		$models = $criteria->getModels();

		if (!empty($models))
		{
			$this->finder->setModels($models);
		}
		return $this->finder->findAll($criteria);
	}

	public function getItemCount($refresh = false)
	{
		return count($this->getData($refresh));
	}

	public function getTotalItemCount()
	{
		if ($this->isStopped)
		{
			$this->totalItemCount = 0;
		}
		if ($this->totalItemCount === null)
		{
			$qb = new QueryBuilder($this->getModel());
			/**
			 * TODO Must count with criteria too!
			 * And multi model
			 */
			$criteria = new SearchCriteria($this->getCriteria());
			$criteria->setLimit(false);
			$criteria->setOffset(false);
			$qb->setCriteria($criteria);
			$this->totalItemCount = $qb->count($this->getCriteria()->getSearch());
		}
		return $this->totalItemCount;
	}

}
