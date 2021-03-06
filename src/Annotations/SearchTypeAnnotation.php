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

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Addendum\Utilities\ClassChecker;
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
		if (!ClassChecker::exists($this->value))
		{
			throw new UnexpectedValueException(sprintf('Could not resolve class name for @SearchType annotation used on model `%s`', $this->getMeta()->type()->name));
		}
		$this->getEntity()->type = $this->value;
	}

}
