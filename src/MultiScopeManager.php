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

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Abstracts\AbstractScopeManager;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * Scope Manager with support for many models
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiScopeManager extends AbstractScopeManager implements ScopeManagerInterface,
		ModelsAwareInterface
{

	use UniqueModelsAwareTrait;

	/**
	 *
	 * @param object $model Base model used for criteria
	 * @param object[] $models Additional models used for criteria
	 */
	public function __construct($model, $models = [])
	{
		$this->setModel($model);
		$this->setModels($models);
	}

	public function getNewCriteria($criteria = null)
	{
		$newCriteria = new SearchCriteria($criteria);
		$newCriteria->decorateWith($this->getModel());
		$newCriteria->setModels($this->getModels());
		return $newCriteria;
	}

	protected function getModelCriteria()
	{
		$criteria = new SearchCriteria;

		foreach ($this->getModels() as $model)
		{
			$criteria->mergeWith($this->getOneModelCriteria($model));
		}
		$criteria->setModels($this->getModels());

		if (empty($criteria))
		{
			return $this->getNewCriteria();
		}
		return $criteria;
	}

	private function getOneModelCriteria($model)
	{
		if ($model instanceof WithCriteriaInterface)
		{
			return $model->getDbCriteria();
		}
		elseif ($model instanceof CriteriaAwareInterface)
		{
			return $model->getCriteria();
		}
		else
		{
			return new SearchCriteria;
		}
	}

}
