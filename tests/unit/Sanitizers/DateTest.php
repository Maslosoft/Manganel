<?php

namespace Sanitizers;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\Sanitizers\DateWriteEsSanitizer;
use MongoDate;
use UnitTester;

class DateTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillReadProperDates()
	{
		$sanitizer = new DateWriteEsSanitizer;
		$date = $sanitizer->read(null, '2016-08-03T13:28:14+00:00');

		$this->assertInstanceof(MongoDate::class, $date);

		// NOTE: Just check date, as TZ might be different
		$this->assertSame('2016-08-03', date('Y-m-d', $date->sec));
		codecept_debug(date('c', $date->sec));
	}

}
