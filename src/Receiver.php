<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

/**
 * Receiver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Receiver
{

	/**
	 * @ReactOn(Maslosoft\Mangan\Signals\AfterSave)
	 * @param \Maslosoft\Mangan\Signals\AfterSave $signal
	 */
	public function onSave(\Maslosoft\Mangan\Signals\AfterSave $signal)
	{
		$signal->model;
	}

	/**
	 * @ReactOn(Maslosoft\Mangan\Signals\AfterDelete)
	 * @param \Maslosoft\Mangan\Signals\AfterDelete $signal
	 */
	public function onDelete(\Maslosoft\Mangan\Signals\AfterDelete $signal)
	{
		$signal->model;
	}

}
