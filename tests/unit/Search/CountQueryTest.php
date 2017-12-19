<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoId;
use UnitTester;

class CountQueryTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillCountAllDocuments()
	{
		$model = $this->prepare();

		$q = new QueryBuilder($model);


		/* @var $result SimpleModel[] */
		$result = $q->count();
		codecept_debug($result);
		$this->assertSame(7, $result, 'That all cities have been counted');
		unset($result);
	}

	public function testIfWillCountDocuments()
	{
		$model = $this->prepare();

		$q = new QueryBuilder($model);

		/* @var $result SimpleModel[] */
		$result = $q->count('new');
		codecept_debug($result);
		$this->assertSame(3, $result, 'That cities have been counted');
		unset($result);
	}

	private function prepare()
	{
		$cities = [
			'New York',
			'Tokyo',
			'Los Angeles',
			'Paris',
			'Shanghai',
			'New Delhi',
			'New Hempshire'
		];

		foreach ($cities as $city)
		{
			$model = new SimpleModel();
			$model->_id = new MongoId;
			$model->title = $city;

			$im = new IndexManager($model);
			$im->index();
		}

		// Need to wait here, or it will fail...
		
		return $model;
	}

}
