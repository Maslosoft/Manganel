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

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\OperatorDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

/**
 * ConditionDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionDecorator implements ConditionDecoratorInterface, ManganelAwareInterface
{

	use ManganelAwareTrait;

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		foreach ($criteria->getConditions() as $name => $value)
		{
			$decorators = (new PluginFactory())->instance($this->manganel->decorators, $criteria, [
				OperatorDecoratorInterface::class
			]);
			$condition = [];
			foreach ($decorators as $decorator)
			{
				/* @var $decorator OperatorDecoratorInterface */
				if ($decorator->useWith($name, $value))
				{
					$decorator->decorate($condition, $name, $value);
				}
			}
			if (empty($condition))
			{
				continue;
			}
			$conditions[] = $condition;
		}
	}

	public function getKind()
	{
		return self::KindFilter;
	}

}
