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
 * ConditionDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionDecorator implements DecoratorInterface
{

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		foreach ($criteria->getConditions() as $name => $value)
		{
			$conditions[] = [
				'term' => [
					$name => $value
				]
			];
		}
	}

	public function getKind()
	{
		return 'filter';
	}

}
