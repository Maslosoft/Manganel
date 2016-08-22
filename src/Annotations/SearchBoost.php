<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Manganel\Meta\ManganelTypeAnnotation;

/**
 * SearchBoost
 * 
 * Use this annotation to increase or decrease importance of field in search
 * results. Use positive values to increase importance, and negative to
 * decrease.
 * 
 * Increase importance example:
 * 
 * ```php
 * @SearchBoost(3)
 * ```
 * 
 * Decrease importance example:
 * 
  ```php
 * @SearchBoost(-3)
 * ```
 * 
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchBoost extends ManganelTypeAnnotation
{

	const Ns = __NAMESPACE__;

	public function init()
	{
		throw new \Exception('Not implemented');
	}

}
