<?php

namespace Maslosoft\Manganel\Decorators;

use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Manganel\Interfaces\IndexAwareInterface;

/**
 * IndexDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexDecorator implements ModelDecoratorInterface
{

	/**
	 * Key used for score
	 */
	const Key = '__index';

	public function read($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		if ($model instanceof IndexAwareInterface)
		{
			if (array_key_exists(self::Key, $dbValues))
			{
				$model->setIndex($dbValues[self::Key]);
			}
		}
	}

	public function write($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{

	}

}
