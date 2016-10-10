<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Interfaces\QueryBuilder\DecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * SearchDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchDecorator implements DecoratorInterface
{

	const Ns = __NAMESPACE__;

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		$q = $criteria->getSearch();

		if (empty($q))
		{
			// Match all documents if query is null
			$conditions[] = [
				'match_all' => []
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

}
