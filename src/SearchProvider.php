<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Traits\DataProvider\ConfigureTrait;
use Maslosoft\Mangan\Traits\DataProvider\CriteriaTrait;
use Maslosoft\Mangan\Traits\DataProvider\DataTrait;
use Maslosoft\Mangan\Traits\DataProvider\PaginationTrait;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use Maslosoft\Mangan\Traits\SortAwareTrait;
use Maslosoft\Manganel\Helpers\Debug\DebugSearchProvider;

/**
 * SearchProvider
 *
 * @method SearchCriteria getCriteria()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchProvider implements DataProviderInterface
{

	use ConfigureTrait,
	  DataTrait,
	  ModelAwareTrait,
	  PaginationTrait,
	  SortAwareTrait,
	  CriteriaTrait
	{
		CriteriaTrait::setCriteria as traitSetCriteria;
	}

	const CriteriaClass = SearchCriteria::class;

	/**
	 * Finder instance
	 *
	 * This is protected, not private so it can be debugged
	 * @see DebugSearchProvider
	 * @var SearchFinder
	 */
	protected $finder = null;

	/**
	 * Total items count cache
	 * @var int
	 */
	private $totalItemCount = null;

	/**
	 * When stopped no data will be retrieved. Used by external libraries.
	 * @var boolean
	 */
	private $isStopped = false;

	public function __construct($modelClass = null, $config = [])
	{
		if (is_array($modelClass))
		{
			$models = $modelClass;
		}
		else
		{
			$models = [$modelClass];
		}
		foreach ($models as $modelClass)
		{
			$this->configure($modelClass, $config);
		}
		$this->finder = new SearchFinder($models);
		$criteria = $this->getCriteria();
		assert($criteria instanceof SearchCriteria);
		$criteria->setModels($models);
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

	public function setCriteria($criteria)
	{
		assert($criteria instanceof SearchCriteria);
		$criteria->setModels($this->finder->getModels());
		return $this->traitSetCriteria($criteria);
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
			$criteria->setModels($this->finder->getModels());
			$criteria->setLimit(false);
			$criteria->setOffset(false);
			$qb->setCriteria($criteria);
			$this->totalItemCount = $qb->count($this->getCriteria()->getSearch());
		}
		return $this->totalItemCount;
	}

}
