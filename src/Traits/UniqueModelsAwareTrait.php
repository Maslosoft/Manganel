<?php

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Use this trait to provide mutli-model feature to classes.
 *
 * NOTE: It will keep only one instance type in models
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait UniqueModelsAwareTrait
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
	 * @param type $models
	 * @return $this
	 */
	public function setModels($models)
	{
		// Unique based on class name
		// See: http://stackoverflow.com/a/2561283/5444623
		$unique = array_intersect_key($models, array_unique(array_map('get_class', $models)));
		$this->models = array_values($unique);
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
		if (!$this->hasModel($model))
		{
			$this->models[] = $model;
			return true;
		}
		return false;
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
			if ($existing instanceof $model)
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
			if ($existing instanceof $model)
			{
				return true;
			}
		}
		return false;
	}

}
