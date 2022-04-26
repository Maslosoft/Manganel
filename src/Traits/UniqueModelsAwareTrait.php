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
 * Use this trait to provide multi-model feature to classes.
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
	private array $models = [];

	/**
	 * Annotated models
	 *
	 * @return array
	 */
	public function getModels(): array
	{
		return $this->models;
	}

	/**
	 *
	 * @param AnnotatedInterface[] $models
	 * @return $this
	 */
	public function setModels($models): self
	{
		// Unique based on class name
		// See: http://stackoverflow.com/a/2561283/5444623
		$map = static function($value)
		{
			if (is_object($value))
			{
				return get_class($value);
			}
			return $value;
		};
		$unique = array_intersect_key($models, array_unique(array_map($map, $models)));
		$this->models = array_values($unique);
		return $this;
	}

	/**
	 * Add model to set but only if it's instance is not present in set.
	 *
	 * @param AnnotatedInterface $model
	 * @return boolean Whether model was added
	 */
	public function addModel(AnnotatedInterface $model): bool
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
	public function removeModel(AnnotatedInterface $model): bool
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

		// Should not happen, model not found even if should exist
		return false;
	}

	public function hasModel(AnnotatedInterface $model): bool
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
