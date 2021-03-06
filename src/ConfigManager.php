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

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Signals\ConfigInit;

/**
 * Config Manager can be used to customize configuration of mangan without copying
 * whole configuration file. This allows to merge user defined parts with original
 * configuration. Proper configuration of sanitizers, decorators, filters is
 * crucial for proper mangan operation.
 * 
 * Example of recommended usage:
 * ```php
 * $config = array_replace_recursive(ConfigManager::getDefault(), [
 * 	'filters' => [
 * 		RawArray::class => [
 * 			MyCustomFilter::class,
 * 		],
 * 	],
 * 	'sanitizersMap' => [
 * 		RawArray::class => [
 * 			StringSanitizer::class => HtmlSanitizer::class
 * 		]
 * 	]
 * ]);
 * ```
 *
 * Above example snippet will add MyCustomFilter class to RawArray transformer
 * and remap one sanitizer also for RawArray, while keeping all other configuration
 * as it should be.
 *
 * See also `ConfigInit` signal.
 *
 * @see ConfigInit
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConfigManager
{

	private static $config = null;

	/**
	 * Get mangan built-in configuration as array.
	 * @return array Default mangan configuration
	 */
	public static function getDefault()
	{
		if (null === self::$config)
		{
			self::$config = require __DIR__ . '/config/mangan.cfg.php';
		}
		return self::$config;
	}

}
