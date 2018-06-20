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

namespace Maslosoft\Manganel\Helpers\Debug;

use Maslosoft\Manganel\Adapters\Finder\ElasticSearchAdapter;
use Maslosoft\Manganel\SearchProvider;

/**
 * DebugFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DebugSearchProvider extends SearchProvider
{

	public static function getFormattedParams(SearchProvider $dp)
	{
		$adapter = $dp->finder->getAdapter();
		assert($adapter instanceof ElasticSearchAdapter);
		$params = $adapter->getQueryBuilder()->getParams();
		return json_encode($params, JSON_PRETTY_PRINT);
	}

}
