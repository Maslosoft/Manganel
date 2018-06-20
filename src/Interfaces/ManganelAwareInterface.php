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
