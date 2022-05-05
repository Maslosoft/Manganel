<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder\MoreLikeThis;

use Maslosoft\Manganel\Decorators\QueryBuilder\Common\FieldsBoosting;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use function array_values;

class MltBoostDecorator extends FieldsBoosting implements ConditionDecoratorInterface
{
	public function decorate(&$conditions, SearchCriteria $criteria): void
	{
		$mlt = $criteria->getMoreLike();
		if ($mlt === null)
		{
			return;
		}
		// Ignore if Criteria have *no* boosted fields
		$boosted = $criteria->getBoosted();
		if(empty($boosted))
		{
			return;
		}

		$boostFactors = $this->getBoostFactors($criteria);

		// Do *NOT* fill _all field in boosted fields

		$boostedMlts = [];
		$boostForFields = [];

		// Group same boost fields
		foreach($boostFactors as $fieldName => $boost)
		{
			// Prevent converting key to int
			$boostKey = (string)$boost;
			if(empty($boostForFields[$boostKey]))
			{
				$boostForFields[$boostKey] = [];
			}
			$boostForFields[$boostKey][] = $fieldName;
		}

		foreach($boostFactors as $fieldName => $boost)
		{
			// Prevent converting boost key to int
			$boostKey = (string)$boost;
			$mltData = $mlt->toArray();
			$mltData['fields'] = $boostForFields[$boostKey];
			$mltData['boost'] = $boost;

			// NOTE: Use boost as key, as all MLT fields are grouped by boost
			$boostedMlts[$boostKey] = [
				'more_like_this' => $mltData
			];
		}

		// Assure keys to be numeric
		$boostedMlts = array_values($boostedMlts);

		$conditions = $boostedMlts;
	}


	public function getKind()
	{
		return self::KindShould;
	}
}