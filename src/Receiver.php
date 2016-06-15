<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Mangan\Signals\ConfigInit;

/**
 * Receiver of Mangan signals
 *
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
		(new IndexManager($signal->model))->index();
	}

	/**
	 * @SlotFor(ConfigInit)
	 * @param ConfigInit $signal
	 */
	public function onInit(ConfigInit $signal)
	{
		$signal->apply(require __DIR__ . '/../config/mangan.cfg.php');
	}

}
