<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
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
