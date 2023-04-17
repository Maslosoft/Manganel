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

namespace Maslosoft\Manganel\Sanitizers;

use DateTime;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use MongoDB\BSON\UTCDateTime as MongoDate;

/**
 * UnixDateSanitizer
 *
 * This sanitizer allow storing date in elasticsearch in string format,
 * while reading it as MongoDate object.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DateWriteEsSanitizer extends DateSanitizer
{

	public const ISODate = 'Y-m-d*H:i:s*';

	public function read($model, $dbValue)
	{
		// Don't touch
		if ($dbValue instanceof MongoDate)
		{
			return $dbValue;
		}
		// Assume current time
		if ((int) $dbValue === 0 || empty($dbValue))
		{
			$dbValue = time();
		}
		// Assume timestamp
		if (is_int($dbValue) || (is_string($dbValue) && preg_match('~^\d+$~', $dbValue)))
		{
			return new MongoDate((int) $dbValue * 1000);
		}
		elseif (is_string($dbValue))
		{
			// Assume ISO date
			$dt = DateTime::createFromFormat(DateTime::ISO8601, $dbValue);
			if (empty($dt))
			{
				return new MongoDate(time() * 1000);
			}
			$time = $dt->format('U');
			return new MongoDate($time * 1000);
		}

		// Create from date time string
		$dt = new DateTime($dbValue);
		$time = $dt->format('U');
		return new MongoDate($time * 1000);
	}

	public function write($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			$time = $dbValue->toDateTime()->getTimestamp();
		}
		elseif (!empty($time))
		{
			$time = $dbValue;
		}
		else
		{
			$time = time();
		}
		if (is_int($time))
		{
			$dt = DateTime::createFromFormat('U', $time);
		}
		else
		{
			$dt = DateTime::createFromFormat('c', $time);
		}
		return date('c', (int) (new MongoDate((int) $time * 1000))->toDateTime()->getTimestamp());
	}

}
