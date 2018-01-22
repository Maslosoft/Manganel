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

namespace Maslosoft\Manganel\Helpers;

use MongoId;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * RecursiveFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RecursiveFilter
{

	/**
	 * Transform each occurence of MongoId instance to it's string
	 * representation. This is required, as ElasticSearch anyway cannot
	 * store MongoId's
	 * @param array $data
	 */
	public static function mongoIdToString(array $data)
	{
		// In some cases $value *might* still be mongoId type,
		$callback = function(&$item, $key)
		{
			if ($item instanceof MongoId)
			{
				$item = (string) $item;
			}
		};

		array_walk_recursive($data, $callback);
		return $data;
	}

}
