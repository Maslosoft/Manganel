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
			if(!empty($criteria->getConditions()))
			{
				return;
			}
			if(!empty($criteria->getMoreLike()))
			{
				return;
			}
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
