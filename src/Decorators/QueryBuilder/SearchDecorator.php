<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use stdClass;

/**
 * SearchDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchDecorator implements ConditionDecoratorInterface
{

	const Ns = __NAMESPACE__;

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		$q = $criteria->getSearch();

		if (empty($q))
		{
			// Match all documents if query is null
			// stdClass is used here to get `{}` in json, as `[]` causes bad
			// request exception!
			$conditions[] = [
				'match_all' => new stdClass()
			];
		}
		else
		{
			// Use query string matching
			$conditions[] = [
				'simple_query_string' => [
					'query' => $q
				]
			];
		}
	}

	public function getKind()
	{
		return self::KindMust;
	}

}
