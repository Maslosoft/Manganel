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
