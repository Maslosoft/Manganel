<?php

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Abstracts\AbstractScopeManager;
use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;

/**
 * Search Scope Manager for single model
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchScopeManager extends AbstractScopeManager implements ScopeManagerInterface
{

	protected function getNewCriteria($criteria = null)
	{
		return new SearchCriteria($criteria);
	}

}
