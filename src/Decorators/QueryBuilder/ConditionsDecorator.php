<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Decorators\QueryBuilder\Traits\ConditionsAware;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionsAwareInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * ConditionsDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionsDecorator implements BodyDecoratorInterface, ConditionsAwareInterface
{

	use ConditionsAware;

	public function decorate(&$body, SearchCriteria $criteria)
	{
		$body['query'] = [
			'bool' => [
				'must' => $this->getConditions()
			]
		];
	}

}
