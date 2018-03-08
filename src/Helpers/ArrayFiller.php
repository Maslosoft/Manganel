<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 08.03.18
 * Time: 17:12
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