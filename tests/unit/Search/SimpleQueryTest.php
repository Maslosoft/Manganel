<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SimpleQueryTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindDocument()
	{

		$model = new SimpleModel();
		$model->_id = new MongoId;


		$model->title = 'New York, Tokyo, Los Angeles, Paris, Shanghai';

		$im = new IndexManager($model);
		$im->index();

		$val = $im->get();

		$q = new QueryBuilder($model);

		/* @var $result SimpleModel[] */
		$result = $q->search('tokyo');
		codecept_debug($result);
		$this->assertTrue(count($result) > 0);
		unset($result);
	}

	public function testIfWillNotFindDocument()
	{

		$model = new SimpleModel();
		$model->_id = new MongoId;


		$model->title = 'New York, Tokyo, Los Angeles, Paris, Shanghai';

		$im = new IndexManager($model);
		$im->index();

		$val = $im->get();

		$q = new QueryBuilder($model);

		/* @var $result SimpleModel[] */
		$result = $q->search('kazachstan');
		codecept_debug($result);
		$this->assertFalse(count($result) > 0);
		unset($result);
	}

}
