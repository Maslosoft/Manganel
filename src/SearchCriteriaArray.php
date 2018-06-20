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