<?php

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * Scope Manager with support for many models
 *
 * TODO, maybe it's not even necessary? Will be known when SearchFinder will be
 * implemented...
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiScopeManager implements ScopeManagerInterface
{

	use UniqueModelsAwareTrait;

	public function apply(&$criteria = null)
	{
		//??? Is it necessary??
	}

	public function defaultScope()
	{

	}

	public function reset()
	{

	}

	public function resetScope()
	{

	}

	public function scopes()
	{

	}

}
