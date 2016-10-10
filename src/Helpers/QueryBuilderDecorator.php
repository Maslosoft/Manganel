<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
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
		$decorators = (new PluginFactory())->instance($this->manganel->decorators, $criteria, [
			BodyDecoratorInterface::class
		]);

		foreach ($decorators as $decorator)
		{
			/* @var $decorator BodyDecoratorInterface  */
			if ($decorator instanceof ManganelAwareInterface)
			{
				$decorator->setManganel($this->manganel);
			}
			$decorator->decorate($body, $criteria);
		}
	}

}
