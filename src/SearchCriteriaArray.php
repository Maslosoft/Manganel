<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 23.01.18
 * Time: 13:44
 */

namespace Maslosoft\Manganel;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\Manganel\Meta\ManganelMeta;

class SearchCriteriaArray extends RawArray
{
	protected static function getMeta(AnnotatedInterface $model)
	{
		return ManganelMeta::create($model);
	}
}