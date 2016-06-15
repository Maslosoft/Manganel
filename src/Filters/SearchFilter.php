<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Filters;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Search Filter is meant to filter out non indexable properties from model.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchFilter implements TransformatorFilterInterface
{

	/**
	 * TODO: Should filter out:
	 *
	 * 1. Fields marked with:
	 * ```
	 * @Search(false)
	 * ```
	 * 2. Empty strings
	 * 3. Possibly _class fields, as it might blur search results
	 *
	 * @param AnnotatedInterface $model
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return boolean
	 */
	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return true;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return true;
	}

}
