<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Interfaces\QueryBuilder\OperatorDecoratorInterface;

/**
 * InDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class InDecorator implements OperatorDecoratorInterface
{

	public function useWith($key)
	{
		return $key === '$in';
	}

}
