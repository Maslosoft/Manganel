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

/**
 * InDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class InDecorator implements OperatorDecoratorInterface
{

	public function useWith($key, $value)
	{
		if (!is_array($value))
		{
			return false;
		}
		return array_key_exists('$in', $value);
	}

	public function decorate(&$condition, $name, $value)
	{
		$condition = [
			'terms' => [
				$name => $value['$in']
			]
		];
	}

}
