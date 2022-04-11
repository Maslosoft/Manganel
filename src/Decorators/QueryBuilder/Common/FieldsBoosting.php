<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder\Common;

use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Manganel\SearchCriteria;
use function array_key_exists;
use function key;
use function max;
use function min;

abstract class FieldsBoosting
{

	protected function getBoostFactors(SearchCriteria $criteria): array
	{
		$fields = [];

		$model = $criteria->getModel();

		$boosted = $criteria->getBoosted();

		/* @var $boosted float[] */
		foreach ($boosted as $fieldName => $boost)
		{
			if ($boost !== 1.0)
			{
				// Decorate fields, so for instance i18n fields
				// will map boosting to `title.en` etc.
				if($model !== null)
				{
					$cd = new ConditionDecorator($model);
					$name = key($cd->decorate($fieldName));
				}
				else
				{
					// Fallback to non-decorated fields if model is not provided
					$name = $fieldName;
				}
				$fields[$name] = $this->unify($name, $boost, $fields);
			}
		}
		return $fields;
	}

	/**
	 * Unify boost value if using multi model search with different boost
	 * values for field with same name.
	 * @param string $field
	 * @param float $boost
	 * @param float[] $fields
	 * @return float
	 */
	private function unify($field, $boost, $fields)
	{
		if (!array_key_exists($field, $fields))
		{
			// No boost set yet, simply set value
			return $boost;
		}

		// There are already some boost set, decide whether to use maximum
		// or minimum value
		$current = $fields[$field];
		switch (true)
		{
			// Choose minimal boost out of all
			case $current < 1 && $boost < 1:
				$boost = min($current, $boost);
				break;
			// Choose maximum boost out of all
			case $current > 1 && $boost > 1:
				$boost = max($current, $boost);
				break;
			// Choose average, as there is no way to decide whether boost should
			// be above one or below
			default:
				$boost = ($current + $boost) / 2;
		}
		return $boost;
	}
}