<?php

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package   maslosoft/manganel
 * @license   AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link      https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;
use Maslosoft\Manganel\Traits\ModelsAwareTrait;
use function array_unique;

/**
 * MultiModelDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiModelDecorator implements ManganelAwareInterface,
	ModelsAwareInterface
{

	use ManganelAwareTrait,
		ModelsAwareTrait;

	public function __construct($models)
	{
		$this->models = $models;
	}

	public function decorate(&$body, SearchCriteria $initialCriteria): void
	{
		$queries = [];
		$types = [];
		foreach ($this->models as $model)
		{
			$modelCriteria = null;
			if ($model instanceof CriteriaAwareInterface)
			{
				$modelCriteria = $model->getCriteria();
			}
			elseif ($model instanceof WithCriteriaInterface)
			{
				$modelCriteria = $model->getDbCriteria(false);
			}
			if ($modelCriteria === null)
			{
				$criteria = $initialCriteria;
			}
			else
			{
				$criteria = clone $initialCriteria;
				$criteria->mergeWith($modelCriteria);
			}
			$criteria->setModel($model);
			$partial = [];
			(new SingleModelDecorator())
				->setManganel($this->manganel)
				->decorate($partial, $criteria);

			$query = $partial['query'];
			$types[] = TypeNamer::nameType($model);
			$queries[] = $query;
		}

		// NOTE: It is important to have regular array here
		$queries = array_values(array_unique($queries, SORT_REGULAR));

		$body['query']['bool'] = [
			'must' => [
				'dis_max' => [
					'queries' => $queries
				]
			],
			'filter' => [
				'terms' => [
					IndexManager::TypeField => $types
				]
			]
		];

		$common = [];
		(new SingleModelDecorator)->setManganel($this->getManganel())->decorate($common, $initialCriteria);
		unset($common['query']);

		//Use foreach here, as $body is passed by ref
		foreach ($common as $key => $value)
		{
			$body[$key] = $value;
		}
	}

}
