<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

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
class Receiver
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

	private function attachTrashHandlers()
	{
		static $once = true;
		if ($once)
		{
			$handler = function(ModelEvent $event)
			{
				/* @var $event ModelEvent */
				$model = $event->sender;
				$this->onDelete(new AfterDelete($model));
				$event->handled = true;
				$event->isValid = true;
			};
			$handler->bindTo($this);
			Event::on(TrashableTrait::class, TrashInterface::EventAfterTrash, $handler);
			$once = false;
		}
	}

}
