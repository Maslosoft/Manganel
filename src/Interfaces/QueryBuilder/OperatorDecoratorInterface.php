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
	 */
	public function useWith($key);
}
