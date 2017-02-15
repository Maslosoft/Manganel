<?php

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
