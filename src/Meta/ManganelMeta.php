<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

}
