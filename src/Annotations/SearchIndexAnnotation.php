<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Annotations;

use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\Meta\ManganelTypeAnnotation;

/**
 * Search Index Annotation
 *
 * This annotation must be set to index document. Might be with empty value.
 * Use this to set Manganel configuration ID. This does **not** set index name,
 * use `Manganel`'s property `name` to set index name.
 *
 * Most simple example:
 * ```
 * @SearchIndex
 * ```
 *
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchIndexAnnotation extends ManganelTypeAnnotation
{

	const Ns = __NAMESPACE__;

	/**
	 * Index ID. This points to Manganel configuration instance.
	 * @var string
	 */
	public $value = null;

	public function init()
	{
		if (null === $this->value)
		{
			$this->value = Manganel::DefaultIndexId;
		}
		$this->_entity->indexId = $this->value;
	}

}
