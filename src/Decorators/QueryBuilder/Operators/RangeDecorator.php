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

class RangeDecorator implements OperatorDecoratorInterface
{

	/**
	 * Whether to use this operator with current key
	 * @param string $key
	 * @param mixed $value
	 */
	public function useWith($key, $value)
	{
		if (!is_array($value))
		{
			return false;
		}
		$hasOps = [
			array_key_exists('$gt', $value),
			array_key_exists('$gte', $value),
			array_key_exists('$lt', $value),
			array_key_exists('$lte', $value)
		];
		return array_sum($hasOps) > 0;
	}

	public function decorate(&$condition, $name, $value)
	{
		$range = [];
		foreach ($value as $op => $val)
		{
			$newOp = str_replace('$', '', $op);
			$range[$newOp] = $val;
		}
		$condition = [
			'range' => [
				$name => $range
			]
		];
	}
}