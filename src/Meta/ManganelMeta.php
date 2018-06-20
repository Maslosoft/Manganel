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

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Manganel\Options\ManganelMetaOptions;

/**
 * Manganel metadata container class
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ManganelMeta extends Meta
{

	/**
	 * Create instance of Metadata specifically designed for Manganel
	 * @param string|object|AnnotatedInterface $model
	 * @param MetaOptions $options
	 * @return ManganelMeta
	 */
	public static function create($model, MetaOptions $options = null)
	{
		if (null === $options)
		{
			$options = new ManganelMetaOptions();
		}
		return parent::create($model, $options);
	}

	/**
	 * Get field by name
	 * @param string $name
	 * @return DocumentPropertyMeta
	 */
	public function field($name)
	{
		return parent::field($name);
	}

	/**
	 * Get document type meta
	 * @return DocumentTypeMeta
	 */
	public function type()
	{
		return parent::type();
	}

	/**
	 * Get method meta data
	 * @param type $name
	 * @return DocumentMethodMeta
	 */
	public function method($name)
	{
		return parent::method($name);
	}

}
