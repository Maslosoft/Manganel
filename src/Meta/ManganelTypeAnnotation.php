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

namespace Maslosoft\Manganel\Meta;

/**
 * ManganTypeAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganelTypeAnnotation extends ManganelAnnotation
{

	/**
	 * Annotations entity, it can be either class, property, or method
	 * Its concrete annotation implementation responsibility to decide what to do with it.
	 * @return DocumentTypeMeta
	 */
	public function getEntity()
	{
		return parent::getEntity();
	}

}
