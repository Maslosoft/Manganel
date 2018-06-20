<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.06.18
 * Time: 16:15
 */

namespace Maslosoft\Manganel\Helpers;


use function array_merge;
use function in_array;
use function is_array;
use function is_string;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\Manganel\Meta\DocumentPropertyMeta;
use Maslosoft\Manganel\Meta\ManganelMeta;
use MongoId;
use function strpos;

class TextExtractor
{
	private static $banFields = [
		'_class',
		'id',
		'_id',
		'createDate',
		'updateDate',
		'createUser',
		'createUserId',
		'updateUser',
		'updateUserId',
		'wasSaved'
	];

	/**
	 * Extract text values from model with $minBoost for field
	 * @param AnnotatedInterface $model
	 * @param float              $minBoost
	 * @return string[]
	 */
	public static function extract(AnnotatedInterface $model, $minBoost = 1.0)
	{
		$texts = [];

		foreach(ManganelMeta::create($model)->fields() as $metaProperty)
		{
			/* @var $metaProperty DocumentPropertyMeta */
			$name = $metaProperty->name;

			// Skip system fields
			if(in_array($name, self::$banFields))
			{
				continue;
			}

			if($metaProperty->searchBoost < $minBoost)
			{
				continue;
			}
			if(!$metaProperty->searchable)
			{
				continue;
			}

			$values = $model->$name;
			if(!is_array($values) && !is_string($values))
			{
				continue;
			}
			if(!is_array($values) && is_string($values))
			{
				$values = [$values];
			}
			foreach($values as $value)
			{
				if (is_string($value))
				{
					// Skip class names
					if(strpos($value, '\\') !== false && ClassChecker::exists($value))
					{
						continue;
					}
					if(empty($value))
					{
						continue;
					}

					// Ignore ID's
					if(MongoId::isValid($value))
					{
						continue;
					}
					$texts[] = $value;
				}
			}
		}

		foreach(new CompositionIterator($model) as $subModel)
		{
			$texts = array_merge($texts, self::extract($subModel, $minBoost));
		}

		return $texts;
	}
}