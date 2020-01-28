<?php


namespace Maslosoft\Manganel\Decorators\QueryBuilder;


use function array_keys;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use function in_array;

class SelectDecorator implements BodyDecoratorInterface
{
	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		$fields = array_keys($criteria->getSelect());
		if (empty($fields))
		{
			return;
		}
		// Ensure _class field as it is required by SearchFinder
		if(!in_array('_class', $fields, true))
		{
			$fields[] = '_class';
		}
		$c = $criteria->getConditions();
		// Empty conditions results in match_all, which fails when there is a _source too
		// Exception request does not support [_source]
		if(!empty($c))
		{
			$conditions['_source'] = $fields;
		}
	}
}