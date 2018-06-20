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
 * This is default decorator for queries, this provides better "As You Type"
 * experience. If it's not desired, use `QueryDecorator` instead.
 *
 * @see QueryDecorator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrefixQueryDecorator implements QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria)
	{
		$q = $criteria->getSearch();

		// Add `*` only if :
		// - not contain wildcard on end
		// - not have space on end
		// - ends with letter
		// NOTE: Passing two wildcards to query will yield nothing,
		// thats why it is checked too.
		if (!preg_match('~\*$~', $q))
		{
			// Add `*` only if ends with any alphabet letter (phrase_prefix)
			if (preg_match('~\p{L}$~', $q))
			{
				$q = $q . '*';
			}
		}
		$queryStringParams['query'] = $q;
	}

}
