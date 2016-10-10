<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\DecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

/**
 * ConditionsDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionsDecorator implements BodyDecoratorInterface, ManganelAwareInterface
{

	use ManganelAwareTrait;

	public function decorate(&$body, SearchCriteria $criteria)
	{
		$decorators = (new PluginFactory())->instance($this->getManganel()->decorators, $criteria, [
			DecoratorInterface::class
		]);

		$bool = [];
		foreach ($decorators as $decorator)
		{
			/* @var $decorator DecoratorInterface  */
			if ($decorator instanceof ManganelAwareInterface)
			{
				$decorator->setManganel($this->getManganel());
			}
			$conditions = [];
			$decorator->decorate($conditions, $criteria);
			$kind = $decorator->getKind();
			if (empty($bool[$kind]))
			{
				$bool[$kind] = [];
			}
			$bool[$kind] = array_merge($bool[$kind], $conditions);
		}
		codecept_debug($bool);
		$body['query'] = [
			'bool' => $bool
		];
	}

}
