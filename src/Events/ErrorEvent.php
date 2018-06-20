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

namespace Maslosoft\Manganel\Events;


use Exception;
use Maslosoft\Mangan\Events\ModelEvent;

class ErrorEvent extends ModelEvent
{
	/**
	 * Exception intercepted by this event
	 * @var Exception
	 */
	public $exception = null;
}