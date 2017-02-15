<?php

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

/**
 * SingleModelDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SingleModelDecorator implements ManganelAwareInterface
{

	use ManganelAwareTrait;

	public function decorate(&$body, SearchCriteria $criteria)
	{
		$decorators = (new PluginFactory())->instance($this->getManganel()->decorators, $criteria, [
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
