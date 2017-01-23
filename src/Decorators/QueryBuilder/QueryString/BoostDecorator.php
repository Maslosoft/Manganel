<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder\QueryString;

use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Manganel\Interfaces\QueryBuilder\QueryStringDecoratorInterface;
use Maslosoft\Manganel\Meta\ManganelMeta;
use Maslosoft\Manganel\SearchCriteria;

/**
 * BoostDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BoostDecorator implements QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria)
	{
		$fields = [];
		$models = $criteria->getModels();
		foreach ($models as $model)
		{
			$meta = ManganelMeta::create($model);
			$boosted = $meta->properties('searchBoost');
			/* @var $boosted float[] */
			foreach ($boosted as $fieldName => $boost)
			{
				if ($boost !== 1.0)
				{
					// Decorate fields, so for instance i18n fields
					// will map boosting to `title.en` etc.
					$cd = new ConditionDecorator($model);
					$name = key($cd->decorate($fieldName));
					$fields[$name] = $this->unify($name, $boost, $fields);
				}
			}
		}

		// No custom boost, ignore
		if (empty($fields))
		{
			return;
		}
		$boosts = [];
		foreach ($fields as $name => $boost)
		{
			$boosts[] = sprintf('%s^%f', $name, $boost);
		}

		// Add also _all or it would search only boosted fields
		$boosts[] = '_all';

		$queryStringParams['fields'] = $boosts;
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
