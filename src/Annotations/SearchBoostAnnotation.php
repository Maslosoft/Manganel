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

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Manganel\Meta\ManganelPropertyAnnotation;

/**
 * Use this annotation to increase or decrease importance of field in search
 * results. Use above one values to increase importance, and below one to
 * decrease.
 *
 * Default value for query boosting is 1.0, using decimal values is perfectly
 * fine. In fact it's required for demoting results.
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
 * @SearchBoost(0.3)
 * ```
 *
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchBoostAnnotation extends ManganelPropertyAnnotation
{

	const Ns = __NAMESPACE__;

	/**
	 * Search boosting value for a field
	 * @var float
	 */
	public $value = 1.0;

	public function init()
	{
		assert(is_numeric($this->value), sprintf('@SearchBoost must be numeric value, on property: `%s::$%s`', $this->getMeta()->type()->name, $this->name));
		$this->getEntity()->searchBoost = (float) $this->value;
	}

}
