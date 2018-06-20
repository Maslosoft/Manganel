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
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Manganel\Meta\ManganelMeta;
use Maslosoft\Manganel\SearchArray;

/**
 * ElasticSearch 2.x does not allow `_id` fields, this is to prevent storing such fields.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UnderscoreIdFieldDecorator implements ModelDecoratorInterface
{

	/**
	 * Key used instead of `_id`
	 */
	const Key = '_mongo_id_';

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
		if (isset($dbValues[self::Key]) && isset($model->_id))
		{
			$id = $dbValues[self::Key];
			// Need to sanitize value, as $dbValues contains raw value
			$sanitizer = new Sanitizer($model, SearchArray::class, ManganelMeta::create($model));
			$model->_id = $sanitizer->read('_id', $id);
			unset($dbValues[self::Key]);
		}
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
		if (isset($dbValues['_id']))
		{
			$dbValues[self::Key] = $dbValues['_id'];
			unset($dbValues['_id']);
		}
	}

}
