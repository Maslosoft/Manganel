<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 15.06.18
 * Time: 14:48
 */

namespace Maslosoft\Manganel\Helpers;


use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use function json_decode;
use Maslosoft\Manganel\Manganel;
use function str_replace;

class ExceptionDecorator
{
	public static function getDecorated(Manganel $manganel, BadRequest400Exception $exception, $params)
	{
		// Throw previous exception,
		// as it holds more meaningful information
		$json = json_encode($params, JSON_PRETTY_PRINT);

		$msg = $exception->getMessage();

		$decoded = json_decode($msg);
		if(!empty($decoded) && !empty($decoded->error->root_cause[0]->reason))
		{
			$msg = $decoded->error->root_cause[0]->reason;
		}

		$prevMsg = '';
		$previous = $exception->getPrevious();
		if(!empty($previous))
		{
			$prevMsg = '(' . $previous->getMessage() . ')';
		}

		$params = [
			$msg . ' ' . $prevMsg,
			$manganel->indexId,
			$json
		];


		$message = vsprintf("Exception %s while querying `%s`: \n%s\n", $params);
		return new BadRequest400Exception($message, 400, $exception);
	}
}