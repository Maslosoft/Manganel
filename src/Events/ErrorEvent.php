<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 19.06.18
 * Time: 08:39
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