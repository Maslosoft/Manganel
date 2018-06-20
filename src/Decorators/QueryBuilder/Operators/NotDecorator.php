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

namespace Maslosoft\Manganel\Decorators\QueryBuilder\Operators;


use Maslosoft\Manganel\Interfaces\QueryBuilder\OperatorDecoratorInterface;

class NotDecorator implements OperatorDecoratorInterface
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
		return array_key_exists('$ne', $value);
	}

	public function decorate(&$condition, $name, $value)
	{
		$condition = [
			'bool' => [
				'must_not' => [
					'term' => [
						$name => $value['$ne']
					]
				]
			]
		];
	}
}