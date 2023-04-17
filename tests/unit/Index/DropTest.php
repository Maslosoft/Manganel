<?php

namespace Index;

use Codeception\TestCase\Test;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\Manganel;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class DropTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillDropIndex()
	{
		$model = new SimpleModel();
		$model->_id = new MongoId();
		$model->title = 'Jersey';
		$im = new IndexManager($model);

		$im->index();

		$found = $im->get();

		// Check if is indexed
		$this->assertTrue($found instanceof SimpleModel);
		$this->assertSame($model->title, $found->title);

		$result = Manganel::create($model)->drop();
		codecept_debug($result);

		try
		{
			$im->get();
			$this->assertFalse(true);
		}
		catch (Missing404Exception $ex)
		{
			$this->assertTrue(true);
		}
	}

}
