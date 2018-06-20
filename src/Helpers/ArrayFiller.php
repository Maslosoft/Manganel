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

namespace Maslosoft\Manganel\Helpers;


use function array_key_exists;

class ArrayFiller
{
	/**
	 * Fill array by path. This will
	 * create array keys if not already set.
	 *
	 * @param $array
	 * @param $path
	 * @param $value
	 */
	public static function fill($array, $path, $value)
	{
		$parts = explode('.', $path);
		$key = array_shift($parts);
		if (empty($path))
		{
			return $value;
		}
		if (!array_key_exists($key, $array))
		{
			$array[$key] = self::fill($array, implode('.', $parts), $value);
		}
		return $array;
	}
}