<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Manganel\Meta\ManganelTypeAnnotation;
use UnexpectedValueException;

/**
 * Search Type Annotation
 *
 * Use this annotation to override searched type. This can be used
 * to allow search based on hierarchy of models. So that partial
 * models could be passed as parameter to query builder and it
 * will search for proper type.
 *
 * Type should be class name, or dot notation name.
 *
 * Example usage:
 * ```
 * @SearchType(MyVendror\MyPackage\MyDerivedClass)
 * ```
 *
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchTypeAnnotation extends ManganelTypeAnnotation
{

	const Ns = __NAMESPACE__;

	/**
	 * Document type.
	 * @var string
	 */
	public $value = null;

	public function init()
	{
		if (empty($this->value))
		{
			throw new UnexpectedValueException(sprintf('@SearchType annotation requires type name as param, used on model `%s`', $this->getMeta()->type()->name));
		}
		$this->getEntity()->type = $this->value;
	}

}
