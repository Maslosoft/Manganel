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

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * TypeNamer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TypeNamer extends CollectionNamer
{

	public static function nameType($model)
	{
		$enforcedType = ManganelMeta::create($model)->type()->type;
		if (!empty($enforcedType))
		{
			$model = new $enforcedType;
		}
		$collectionName = parent::nameCollection($model);
		return str_replace('.', '_', $collectionName);
	}

}
