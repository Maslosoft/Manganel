<?php

namespace Connection;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\Manganel;
use UnitTester;

class ConnectTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfCanPing()
	{
		$manganel = Manganel::fly();

		$this->assertTrue($manganel->getClient()->ping());
	}

	public function testIfCanStoreSomething()
	{
		$mnl = Manganel::fly();

		$src = array();
		$src['body'] = array('testField' => 'abc');
		$src['index'] = $mnl->index;
		$src['type'] = 'my_type';
		$src['id'] = 'my_id';

		$client = $mnl->getClient();

		$client->index($src);

		$getParams = array();
		$getParams['index'] = $mnl->index;
		$getParams['type'] = 'my_type';
		$getParams['id'] = 'my_id';

		$doc = $client->get($getParams);

		$this->assertSame($src['body']['testField'], $doc['_source']['testField']);
	}

}
