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

/**
 * Implement this interface to provide index for:
 *
 * * Models via SearchProvider
 * 
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IndexAwareInterface
{

	/**
	 * Get currently used index
	 * @return string
	 */
	public function getIndex();

	/**
	 * Set currently used index
	 * @param string $index
	 * @return static
	 */
	public function setIndex($index);
}
