<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * SearchArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchArray extends RawArray
{

	protected static function getMeta(AnnotatedInterface $model)
	{
		return ManganelMeta::create($model);
	}

}
