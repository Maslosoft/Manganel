<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * ScrollDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScrollDecorator implements BodyDecoratorInterface
{

	public function decorate(&$body, SearchCriteria $criteria)
	{
		if ($criteria->getLimit() || $criteria->getOffset())
		{
			if (is_int($criteria->getOffset()))
			{
				$body['from'] = $criteria->getOffset();
			}
			if (is_int($criteria->getLimit()))
			{
				$body['size'] = $criteria->getLimit();
			}
		}
	}

}
