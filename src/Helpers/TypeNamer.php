<?php

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Mangan\Helpers\CollectionNamer;

/**
 * TypeNamer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TypeNamer extends CollectionNamer
{

	public static function nameType($model)
	{
		$collectionName = $this->nameCollection($model);
		return str_replace('.', '_', $collectionName);
	}

}
