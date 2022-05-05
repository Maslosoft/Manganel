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

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Decorators\QueryBuilder\MoreLikeThis\MltBoostDecorator;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

class MoreLikeThisDecorator implements ConditionDecoratorInterface,
	ManganelAwareInterface

{
	use ManganelAwareTrait;

	public const Ns = __NAMESPACE__;

	/**
	 * @param                $conditions
	 * @param SearchCriteria $criteria
	 * @return void
	 * @see MltBoostDecorator
	 */
	public function decorate(&$conditions, SearchCriteria $criteria): void
	{
		$mlt = $criteria->getMoreLike();
		if ($mlt === null)
		{
			return;
		}
		// Ignore if Criteria have boosted fields
		$boosted = $criteria->getBoosted();
		if (!empty($boosted))
		{
			return;
		}

		$conditions = [
			[
				'more_like_this' => $mlt->toArray()
			]
		];
	}

	public function getKind()
	{
		return self::KindShould;
	}


}