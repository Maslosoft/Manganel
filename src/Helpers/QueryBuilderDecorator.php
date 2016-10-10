<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionsAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\DecoratorInterface;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\SearchCriteria;

/**
 * QueryBuilderDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryBuilderDecorator
{

	/**
	 * Manganel instance
	 * @var Mangangel
	 */
	private $manganel = null;

	public function __construct(Manganel $manganel)
	{
		$this->manganel = $manganel;
	}

	public function decorate(&$body, SearchCriteria $criteria)
	{
		$bodyDecorators = (new PluginFactory())->instance($this->manganel->decorators, $criteria, [
			BodyDecoratorInterface::class
		]);
		$conditionsDecorators = (new PluginFactory())->instance($this->manganel->decorators, $criteria, [
			DecoratorInterface::class
		]);

		$conditions = [];
		foreach ($conditionsDecorators as $decorator)
		{
			/* @var $decorator DecoratorInterface  */
			$decorator->decorate($conditions, $criteria);
		}

		foreach ($bodyDecorators as $decorator)
		{
			/* @var $decorator BodyDecoratorInterface  */
			if ($decorator instanceof ConditionsAwareInterface)
			{
				$decorator->setConditions($conditions);
			}
			$decorator->decorate($body, $criteria);
		}
	}

}
