<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Meta;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 * Mangan metadata container class
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ManganelMeta extends Meta
{

	/**
	 * Create instance of Metadata specifically designed for Mangan
	 * @param IAnnotated $component
	 * @param MetaOptions $options
	 * @return ManganelMeta
	 */
	protected function __construct(IAnnotated $component = null, MetaOptions $options = null)
	{
		if (null === $options)
		{
			$options = new ManganelMetaOptions();
		}
		parent::__construct($component, $options);
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
