<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\QueryStringDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;
use stdClass;

/**
 * SearchDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchDecorator implements ConditionDecoratorInterface,
		ManganelAwareInterface
{

	use ManganelAwareTrait;

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
			// TODO Boost fields #8 https://github.com/Maslosoft/Manganel/issues/8
			// TODO Add `*` only if ends with any alphabet letter (phrase_prefix)
			$decorators = (new PluginFactory())->instance($this->manganel->decorators, $criteria, [
				QueryStringDecoratorInterface::class
			]);
			$queryStringParams = [];
			foreach ($decorators as $decorator)
			{
				/* @var $decorator QueryStringDecoratorInterface */
				$decorator->decorate($queryStringParams, $criteria);
			}
			$conditions[] = [
				'simple_query_string' => $queryStringParams
			];
		}
	}

	public function getKind()
	{
		return self::KindMust;
	}

}
