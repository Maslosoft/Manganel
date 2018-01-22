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

namespace Maslosoft\Manganel\Interfaces\QueryBuilder;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface OperatorDecoratorInterface
{

	/**
	 * Whether to use this operator with current key
	 * @param string $key
	 * @param mixed $value
	 */
	public function useWith($key, $value);

	public function decorate(&$condition, $name, $value);
}
