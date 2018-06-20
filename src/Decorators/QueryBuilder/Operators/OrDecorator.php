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

use function array_merge;
use function codecept_debug;
use Maslosoft\Manganel\Helpers\ArrayFiller;
use Maslosoft\Manganel\Interfaces\QueryBuilder\OperatorDecoratorInterface;

/**
 * OrDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class OrDecorator implements OperatorDecoratorInterface
{

	public function useWith($key, $value)
	{
		return $key == '$or';
	}

	public function decorate(&$condition, $name, $value)
	{
		$should = [];
		foreach($value as $termsSet)
		{
			foreach ($termsSet as $term => $values)
			{
				foreach ($values as $value)
				{
					$should[] = [
						'term' => [
							$term => $value
						]
					];
				}
			}
		}
		$condition = ArrayFiller::fill($condition, 'bool.should', []);

		$condition['bool']['should'] = array_merge($condition['bool']['should'], $should);
	}

}
