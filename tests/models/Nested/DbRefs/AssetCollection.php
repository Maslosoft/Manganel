<?php


namespace Maslosoft\ManganelTest\Models\Nested\DbRefs;


use Maslosoft\Mangan\Document;

/**
 * @SearchIndex
 */
class AssetCollection extends Document
{
	public $title = '';

	/**
	 * @DbRefArray(AssetGroup)
	 * @var AssetGroup[]
	 */
	public $items = [];
}