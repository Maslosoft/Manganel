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

namespace Maslosoft\Manganel\Meta;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * ManganelAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganelAnnotation extends MetaAnnotation
{

	/**
	 * Model metadata object
	 * @return ManganelMeta
	 */
	public function getMeta()
	{
		return parent::getMeta();
	}

}
