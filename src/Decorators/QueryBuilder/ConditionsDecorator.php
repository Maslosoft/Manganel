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
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

/**
 * This decorator provides filtering capabilities for query builder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionsDecorator implements BodyDecoratorInterface,
		ManganelAwareInterface
{

	use ManganelAwareTrait;

	public function decorate(&$body, SearchCriteria $criteria)
	{
		$decorators = (new PluginFactory())->instance($this->getManganel()->decorators, $criteria, [
			ConditionDecoratorInterface::class
		]);

		$bool = [];
		foreach ($decorators as $decorator)
		{
			/* @var $decorator ConditionDecoratorInterface  */
			if ($decorator instanceof ManganelAwareInterface)
			{
				$decorator->setManganel($this->getManganel());
			}
			$conditions = [];
			$decorator->decorate($conditions, $criteria);
			if (empty($conditions))
			{
				continue;
			}
			$kind = $decorator->getKind();
			if(false === $kind)
			{
				continue;
			}
			if (empty($bool[$kind]))
			{
				$bool[$kind] = [];
			}
			foreach($conditions as $condition)
			{
				$bool[$kind][] = $condition;
			}
		}
		if (!empty($bool))
		{
			$body['query'] = [
				'bool' => $bool
			];
		}
	}

}
