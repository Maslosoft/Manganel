<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder\QueryString;

use Maslosoft\Manganel\Interfaces\QueryBuilder\QueryStringDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * NOTE: This decorator is by default disabled. Prefered decorator is
 * PrefixQueryDecorator, which allows better "As You Type" experience.
 *
 * @see PrefixQueryDecorator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryDecorator implements QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria)
	{
		$queryStringParams['query'] = $criteria->getSearch();
	}

}
