<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Manganel\Meta\ManganelTypeAnnotation;

/**
 * SearchAnnotation
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchAnnotation extends ManganelTypeAnnotation
{

	const Ns = __NAMESPACE__;

	/**
	 * Whether to search field
	 * @var boolean
	 */
	public $value = true;

	public function init()
	{
		$this->getEntity()->indexId = $this->value;
	}

}
