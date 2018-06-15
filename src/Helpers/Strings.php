<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 15.06.18
 * Time: 18:49
 */

namespace Maslosoft\Manganel\Helpers;


class Strings
{
	/**
	 *
	 * @param string $input
	 * @param string $separator
	 * @return string
	 */
	public static function decamelize($input, $separator = '-')
	{
		return ltrim(strtolower(preg_replace('/[A-Z]/', "$separator$0", $input)), $separator);
	}

	/**
	 * Will return CamelCased value from `input`.
	 *
	 * Example:
	 *
	 * ```
	 * camelize('my-html-id');
	 * ```
	 *
	 * Will return `MyHtmlId`.
	 *
	 * Can also accept different separator or make first letter lower case:
	 *
	 * ```
	 * camelize('\My\Php\ClassName', '\\', true);
	 * ```
	 *
	 * Will return `myPhpClassName`. This might be useful for generating HTML id's
	 * from PHP class names.
	 *
	 * @param string $input
	 * @param string $separator
	 * @return string
	 */
	public static function camelize($input, $separator = '-', $lcFirst = false)
	{
		assert(is_string($input));
		$spaced = str_replace($separator, ' ', $input);
		$result = str_replace(' ', '', ucwords($spaced));
		if ($lcFirst)
		{
			return lcfirst($result);
		}
		return $result;
	}
}