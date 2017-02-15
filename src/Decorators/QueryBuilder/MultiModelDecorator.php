<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;
use Maslosoft\Manganel\Traits\ModelsAwareTrait;

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

	public function decorate(&$body, SearchCriteria $initialCriteria)
	{
		$queries = [];
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
			if (empty($modelCriteria))
			{
				$criteria = $initialCriteria;
			}
			else
			{
				$criteria = clone $initialCriteria;
				$criteria->mergeWith($modelCriteria);
			}
			$partial = [];
			(new SingleModelDecorator($model))
					->setManganel($this->manganel)
					->decorate($partial, $criteria);
			$query = [
				'bool' => [
					'filter' => [
						'type' => [
							'value' => TypeNamer::nameType($model)
						]
					],
					'must' => $partial['query']
				]
			];
			$queries[] = $query;
		}
		$body['query']['dis_max']['queries'] = $queries;
	}

}
