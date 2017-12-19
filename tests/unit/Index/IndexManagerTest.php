<?php

namespace Index;

use Codeception\TestCase\Test;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoId;
use UnitTester;

class IndexManagerTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfIndexManagerCanCreateRetrieveUpdateDelete()
	{
		$model = new SimpleModel();
		$model->_id = new MongoId();
		$model->title = 'Jersey';
		$im = new IndexManager($model);

		$im->index();

		$found = $im->get();

		$this->assertTrue($found instanceof SimpleModel);
		$this->assertSame($model->title, $found->title);


		$model->title = 'New York, New York';

		$im->index();
		$found = $im->get();
		$this->assertTrue($found instanceof SimpleModel);
		$this->assertSame($model->title, $found->title);

		$im->delete();

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
