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

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Mangan\Signals\ConfigInit;
use Maslosoft\Mangan\Traits\Model\TrashableTrait;

/**
 * Receiver of Mangan signals
 * TODO: Should remove from index on trash
 * TODO: Should add to index on trash restore
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Receiver implements AnnotatedInterface
{

	/**
	 * @SlotFor(AfterSave)
	 * @param AfterSave $signal
	 */
	public function onSave(AfterSave $signal)
	{
		(new IndexManager($signal->model))->index();
	}

	/**
	 * @SlotFor(AfterDelete)
	 * @param AfterDelete $signal
	 */
	public function onDelete(AfterDelete $signal)
	{
		(new IndexManager($signal->model))->delete();
	}

	/**
	 * @SlotFor(ConfigInit)
	 * @codeCoverageIgnore Configuration is tested on Index/SearchArray test
	 * @param ConfigInit $signal
	 */
	public function onInit(ConfigInit $signal)
	{
		$signal->apply(require __DIR__ . '/config/mangan.cfg.php');

		// Trash does not emit signals
		$this->attachTrashHandlers();
	}

	/**
	 * @staticvar boolean $once
	 */
	private function attachTrashHandlers()
	{
		// @codeCoverageIgnoreStart
		static $once = true;
		if ($once)
		{
			$handler = function(ModelEvent $event)
			{
				// @codeCoverageIgnoreEnd
				/* @var $event ModelEvent */
				$model = $event->sender;
				$this->onDelete(new AfterDelete($model));
				$event->handled = true;
				$event->isValid = true;
				// @codeCoverageIgnoreStart
			};
			$handler->bindTo($this);
			Event::on(TrashableTrait::class, TrashInterface::EventAfterTrash, $handler);
			$once = false;
		}
	}

}
