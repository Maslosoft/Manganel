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

namespace Maslosoft\Manganel\Options;

use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Mangan\Options\ManganMetaOptions;
use Maslosoft\Manganel\Annotations\SearchIndexAnnotation;
use Maslosoft\Manganel\Meta\DocumentMethodMeta;
use Maslosoft\Manganel\Meta\DocumentPropertyMeta;
use Maslosoft\Manganel\Meta\DocumentTypeMeta;

/**
 * MetaOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ManganelMetaOptions extends MetaOptions
{

	/**
	 * Meta container class name for type (class)
	 * @var string
	 */
	public $typeClass = DocumentTypeMeta::class;

	/**
	 * Meta container class name for method
	 * @var string
	 */
	public $methodClass = DocumentMethodMeta::class;

	/**
	 * Meta container class name for property
	 * @var string
	 */
	public $propertyClass = DocumentPropertyMeta::class;

	/**
	 * Namespaces for annotations
	 * @var string
	 */
	public $namespaces = [
		SearchIndexAnnotation::Ns
	];

	public function __construct()
	{
		// Include Mangan annotations namespaces too
		$manganOptions = new ManganMetaOptions;
		$this->namespaces = array_merge($manganOptions->namespaces, $this->namespaces);
	}

}
