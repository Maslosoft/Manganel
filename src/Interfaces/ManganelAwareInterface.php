<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Interfaces;

use Maslosoft\Manganel\Manganel;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ManganelAwareInterface
{

	/**
	 * @return Manganel
	 */
	public function getManganel();

	/**
	 *
	 * @param Manganel $manganel
	 * @return $this
	 */
	public function setManganel(Manganel $manganel);
}
