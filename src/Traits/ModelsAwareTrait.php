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

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Use this trait to provide mutli-model feature to classes.
 *
 * NOTE: It will not check for uniqueness of models
 * Use this if uniqueness is ensured already
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ModelsAwareTrait
{

	/**
	 * Annotated model
	 * @var AnnotatedInterface[]
	 */
	private $models = [];

	/**
	 * Annotated models
	 * @var AnnotatedInterface[]
	 */
	public function getModels()
	{
		return $this->models;
	}

	/**
	 *
	 * @param AnnotatedInterface[] $models
	 * @return $this
	 */
	public function setModels($models)
	{
		$this->models = $models;
		return $this;
	}

	/**
	 * Add model to set but only if it's instance is not present in set.
	 *
	 * @param AnnotatedInterface $model
	 * @return boolean Whether model was added
	 */
	public function addModel(AnnotatedInterface $model)
	{
		$this->models[] = $model;
	}

	/**
	 * Remove model from set.
	 *
	 * @param AnnotatedInterface $model
	 * @return boolean Whether model was removed
	 */
	public function removeModel(AnnotatedInterface $model)
	{
		if (!$this->hasModel($model))
		{
			return false;
		}
		foreach ($this->models as $index => $existing)
		{
			if (get_class($existing) === get_class($model))
			{
				unset($this->models[$index]);
				return true;
			}
		}

		// Should not happen, model not found even if should exists
		return false;
	}

	public function hasModel(AnnotatedInterface $model)
	{
		foreach ($this->models as $existing)
		{
			if (get_class($existing) === get_class($model))
			{
				return true;
			}
		}
		return false;
	}

}
