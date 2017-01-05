<?php

namespace Maslosoft\Manganel\Helpers\Debug;

use Maslosoft\Manganel\Adapters\Finder\ElasticSearchAdapter;
use Maslosoft\Manganel\SearchFinder;

/**
 * DebugFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DebugFinder
{

	public static function getFormattedParams(SearchFinder $finder)
	{
		$adapter = $finder->getAdapter();
		assert($adapter instanceof ElasticSearchAdapter);
		$params = $adapter->getQueryBuilder()->getParams();
		return json_encode($params, JSON_PRETTY_PRINT);
	}

}
