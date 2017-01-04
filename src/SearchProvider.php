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

	public function __construct($modelClass = null, $config = [])
	{
		$this->configure($modelClass, $config);
	}

	protected function fetchData()
	{

		$criteria = $this->configureFetch();

		/**
		 * TODO Refactor this into SearchFinder class
		 */
		$qb = new QueryBuilder();
		if ($criteria instanceof SearchCriteria)
		{
			$models = $criteria->getModels();
			if (!empty($models))
			{
				$qb->add($models);
			}
		}
		$model = $this->getModel();
		if (!empty($model) && !Event::handled($model, FinderInterface::EventBeforeFind))
		{
			return [];
		}

		$modelCriteria = null;

		// This check is required for plain php objects
		if ($model instanceof WithCriteriaInterface)
		{
			$modelCriteria = $model->getDbCriteria();
		}

		$criteria->mergeWith($modelCriteria);
		if (!empty($model))
		{
			$qb->add($model);
		}
		$qb->setCriteria($criteria);
		$rawResults = $qb->search($criteria->getSearch());
		$results = [];
		foreach ($rawResults as $data)
		{
			$model = SearchArray::toModel($data['_source']);
			if ($model instanceof IndexAwareInterface)
			{
				$model->setIndex($data['_index']);
			}
			if ($model instanceof ScoreAwareInterface)
			{
				$model->setScore($data['_score']);
			}
			$results[] = $model;
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
