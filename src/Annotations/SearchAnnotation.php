<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
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
		$this->getEntity()->searchable = (bool) $this->value;
	}

}
