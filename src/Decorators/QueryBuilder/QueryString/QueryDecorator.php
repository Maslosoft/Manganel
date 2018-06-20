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
