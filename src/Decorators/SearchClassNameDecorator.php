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

namespace Maslosoft\Manganel\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * SearchClassNameDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchClassNameDecorator implements ModelDecoratorInterface
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param mixed $dbValues
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		return true;
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param AnnotatedInterface $model Model which is about to be decorated
	 * @param mixed[] $dbValues Whole model values from database. This is associative array with keys same as model properties (use $name param to access value). This is passed by reference.
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		// Do not store class names for homogenous collections
		/**
		 * TODO Below code conflicts with some features
		 */
//		if ($transformatorClass === RawArray::class)
//		{
//			$isHomogenous = ManganMeta::create($model)->type()->homogenous;
//			if ($isHomogenous)
//			{
//				return;
//			}
//		}
		$enforcedType = ManganelMeta::create($model)->type()->type;
		if (!empty($enforcedType))
		{
			$dbValues['_class'] = $enforcedType;
		}
		else
		{
			$dbValues['_class'] = get_class($model);
		}
		return true;
	}

}
