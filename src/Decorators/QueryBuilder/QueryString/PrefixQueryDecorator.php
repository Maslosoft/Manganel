<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder\QueryString;

use Maslosoft\Manganel\Interfaces\QueryBuilder\QueryStringDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * This is default decorator for queries, this provides better "As You Type"
 * experience. If it's not deosred, use `QueryDecorator` instead.
 *
 * @see QueryDecorator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrefixQueryDecorator implements QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria)
	{
		$queryStringParams['query'] = $criteria->getSearch() . '*';
	}

}
