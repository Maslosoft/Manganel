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
use MongoDate;

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

	const ISODate = 'Y-m-d*H:i:s*';

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
			return new MongoDate((int) $dbValue);
		}
		elseif (is_string($dbValue))
		{
			// Assume ISO date
			$dt = DateTime::createFromFormat(DateTime::ISO8601, $dbValue);
			if (empty($dt))
			{
				return new MongoDate(time());
			}
			$time = $dt->format('U');
			return new MongoDate($time);
		}

		// Create from date time string
		$dt = new DateTime($dbValue);
		$time = $dt->format('U');
		return new MongoDate($time);
	}

	public function write($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			$time = $dbValue->sec;
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
		return date('c', (int) (new MongoDate((int) $time))->sec);
	}

}
