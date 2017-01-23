<?php

namespace Maslosoft\Manganel\Decorators;

use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Manganel\Interfaces\MaxScoreAwareInterface;

/**
 * MaxScoreDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MaxScoreDecorator implements ModelDecoratorInterface
{

	/**
	 * Key used for score
	 */
	const Key = '__max_score';

	public function read($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		if ($model instanceof MaxScoreAwareInterface)
		{
			if (array_key_exists(self::Key, $dbValues))
			{
				$model->setMaxScore(floatval($dbValues[self::Key]));
			}
		}
	}

	public function write($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{

	}

}
