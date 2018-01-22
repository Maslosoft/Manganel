<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder\Operators;

use Maslosoft\Manganel\Interfaces\QueryBuilder\OperatorDecoratorInterface;
use MongoId;

/**
 * SimpleTermDecorator
 * TODO Should return `term`
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimpleTermDecorator implements OperatorDecoratorInterface
{

	public function useWith($key, $value)
	{
		// Do not use with $* operators
		if (strpos($key, '$') === 0)
		{
			return false;
		}
		// Special case for mongo id...
		if ($value instanceof MongoId)
		{
			return true;
		}
		// Use only with simple values
		return is_scalar($value);
	}

	public function decorate(&$condition, $name, $value)
	{
		$condition = [
			'term' => [
				$name => $value
			]
		];
	}

}
