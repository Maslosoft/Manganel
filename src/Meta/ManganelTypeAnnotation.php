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
	 * 
	 * @return DocumentTypeMeta
	 */
	public function getEntity()
	{
		return parent::getEntity();
	}

}
