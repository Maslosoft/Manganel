<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
