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

namespace Maslosoft\Manganel\Helpers;


use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use function json_decode;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Manganel\Events\ErrorEvent;
use Maslosoft\Manganel\Manganel;

class ExceptionHandler
{

	public static function getDecorated(Manganel $manganel, Exception $exception, $params): BadRequest400Exception
	{
		// Throw previous exception,
		// as it holds more meaningful information
		$json = json_encode($params, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

		$msg = $exception->getMessage();
		$reason = '';
		$decoded = json_decode($msg, false, 512, JSON_THROW_ON_ERROR);
		if (!empty($decoded) && !empty($decoded->error->root_cause[0]->reason))
		{
			$msg = $decoded->error->root_cause[0]->reason;
			if(!empty($decoded->error->reason))
			{
				$reason = $decoded->error->reason;
			}
		}

		$prevMsg = '';
		$previous = $exception->getPrevious();
		if ($previous !== null)
		{
			$prevMsg = '(' . $previous->getMessage() . ')';
		}
		if (!empty($reason))
		{
			$prevMsg .= ' ' . $reason;
		}

		$params = [
			$msg . ' ' . $prevMsg,
			$manganel->indexId,
			$json
		];


		$message = vsprintf("Exception %s while querying `%s`: \n%s\n", $params);
		return new BadRequest400Exception($message, 400, $exception);
	}

	public static function handled(Exception $e, $model = null, $eventName = null)
	{
		if(empty($model))
		{
			return false;
		}
		assert(!empty($eventName));

		$evt = new ErrorEvent($model);
		$evt->exception = $e;
		if(Event::hasHandler($model, $eventName) && Event::handled($model, $eventName, $evt))
		{
			return true;
		}
		return false;
	}
}