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
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\Transformer;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * SearchArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchArray extends Transformer implements TransformatorInterface
{

	protected static function getMeta(AnnotatedInterface $model): ManganMeta
	{
		return ManganelMeta::create($model);
	}

}
