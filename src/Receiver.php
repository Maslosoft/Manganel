<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;

/**
 * Receiver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Receiver
{

	/**
	 * @ReactOn(Maslosoft\Mangan\Signals\AfterSave)
	 * @param AfterSave $signal
	 */
	public function onSave(AfterSave $signal)
	{
		(new IndexManager($signal->model))->index();
	}

	/**
	 * @ReactOn(Maslosoft\Mangan\Signals\AfterDelete)
	 * @param AfterDelete $signal
	 */
	public function onDelete(AfterDelete $signal)
	{
		$signal->model;
	}

}
