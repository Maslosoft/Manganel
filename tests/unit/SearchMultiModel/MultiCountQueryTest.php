<?php

namespace SearchMultiModel;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\ManganelTest\Models\SimpleModel;
use Maslosoft\ManganelTest\Models\SimpleModelSecond;
use MongoId;
use UnitTester;

class MultiCountQueryTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillCountAllDocuments()
	{

		$q = $this->prepare();


		/* @var $result SimpleModel[] */
		$result = $q->count();
		codecept_debug($result);
		$this->assertSame(14, $result, 'That all cities have been counted');
		unset($result);
	}

	public function testIfWillCountDocuments()
	{

		$q = $this->prepare();

		/* @var $result SimpleModel[] */
		$result = $q->count('new');
		codecept_debug($result);
		$this->assertSame(6, $result, 'That cities have been counted');
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

			$model2 = new SimpleModelSecond();
			$model2->_id = new MongoId;
			$model2->title = $city;

			$im2 = new IndexManager($model2);
			$im2->index();
		}

		// Need to wait here, or it will fail...
		
		$q = new QueryBuilder;
		$q->add($model);
		$q->add($model2);
		return $q;
	}

}
