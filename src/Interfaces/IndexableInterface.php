<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Interfaces;

use Maslosoft\Manganel\Signals\IndexableSignal;

/**
 * Implement this interface to make document available for
 * search engine
 * @SearchIndex
 * @SlotFor(IndexableSignal)
 * @see IndexableSignal
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IndexableInterface
{
	
}
