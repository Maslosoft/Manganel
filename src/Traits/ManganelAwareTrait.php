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
