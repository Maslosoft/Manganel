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

use Maslosoft\Manganel\Signals\IndexableSlot;
use Maslosoft\Signals\Interfaces\SignalInterface;

/**
 * Implement this interface to make document available for
 * search engine
 * @SearchIndex
 * @SignalFor(IndexableSlot)
 * @see IndexableSlot
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IndexableInterface extends SignalInterface
{
	
}
