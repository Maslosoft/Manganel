<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Manganel\Manganel;

/**
 * ManganelAwareTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ManganelAwareTrait
{

	/**
	 * Manganel instance
	 * @var Manganel
	 */
	private $manganel = null;

	/**
	 *
	 * @return Manganel
	 */
	public function getManganel()
	{
		return $this->manganel;
	}

	/**
	 *
	 * @param Manganel $manganel
	 * @return $this
	 */
	public function setManganel(Manganel $manganel)
	{
		$this->manganel = $manganel;
		return $this;
	}

}
