<?php

namespace Index;

use Codeception\TestCase\Test;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\ManganelTest\Models\Partial\BaseModel;
use Maslosoft\ManganelTest\Models\Partial\ExtendedModel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class PartialModelTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfIndexManagerCanUpdatePartial()
	{
		$id = new MongoId();
		$model = new ExtendedModel();
		$model->_id = $id;
		$model->url = 'city';
		$model->title = 'Jersey';
		$im = new IndexManager($model);

		$im->index();

		$found = $im->get();

		$this->assertTrue($found instanceof ExtendedModel);
		$this->assertSame($model->title, $found->title);

		// Simulate setting from external source, or loading from db
		$partial = new BaseModel;
		$partial->_id = $id;
		$partial->url = 'myCity';
		$partialIndexer = new IndexManager($partial);
		$partialIndexer->index();

		$found2 = $im->get();
		$this->assertTrue($found2 instanceof ExtendedModel);
		$this->assertSame($model->title, $found2->title);
		$this->assertSame($partial->url, $found2->url);

		$partialIndexer->delete();

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
