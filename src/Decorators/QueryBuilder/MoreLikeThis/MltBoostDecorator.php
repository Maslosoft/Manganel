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

		// NOTE: There are issues with MLT multi-model-multi-boosted-queries, so for now disabled
		// @see MoreLikeThisDecorator
		return;

		$boostFactors = $this->getBoostFactors($criteria);

		// Fill in _all too... or not
//		$boostFactors['_all'] = 1.0;

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

		// Rest keys to be numeric
		$boostedMlts = array_values($boostedMlts);

		\Maslosoft\Components\Helpers\Dump::js($conditions);

		$conditions = $boostedMlts;
//			$boostedMlts
//			];
//
//		$conditions = [
//			'dis_max' => [
//				'queries' => $boostedMlts
//			]
//		];

		\Maslosoft\Components\Helpers\Dump::js($conditions);

//		$conditions = [
//			[
//				'more_like_this' => $mlt->toArray()
//			]
//		];
	}


	public function getKind()
	{
		return self::KindMust;
	}
}