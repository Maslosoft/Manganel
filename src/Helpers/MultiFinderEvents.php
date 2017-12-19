<?php

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\FinderEvents;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;

/**
 * Finder events helper supporting multiple models.
 * Requires finder to implement `ModelsAwareInterface`
 *
 * @see ModelsAwareInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiFinderEvents extends FinderEvents
{

	protected function trigger($finder, $event)
	{
		assert($finder instanceof ModelsAwareInterface);
		foreach ($finder->getModels() as $model)
		{
			Event::trigger($model, $event);
		}
	}

	protected function handle($finder, $event)
	{
		assert($finder instanceof ModelsAwareInterface);
		foreach ($finder->getModels() as $model)
		{
			if (!$this->handleOne($model, $event))
			{
				return false;
			}
		}
		return true;
	}

	protected function handleOne($model, $eventName)
	{
		if (!Event::hasHandler($model, $eventName))
		{
			return true;
		}
		$event = new ModelEvent();
		Event::trigger($model, $eventName, $event);
		return $event->isValid || $event->handled;
	}

}
