<?php

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
