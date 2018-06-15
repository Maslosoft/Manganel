<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 30.05.18
 * Time: 13:34
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

class MoreLikeThisDecorator implements ConditionDecoratorInterface,
	ManganelAwareInterface

{
	use ManganelAwareTrait;

	const Ns = __NAMESPACE__;

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		$mlt = $criteria->getMoreLike();
		if (empty($mlt))
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