<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\ModelWithIndex;
use Maslosoft\ManganelTest\Models\ModelWithScore;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SearchProviderIndexTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindDocumentsAndSetItsIndexName()
	{
		$model = $this->prepare();

		$criteria = new SearchCriteria();
		$criteria->search('shanghai');

		$dp = new SearchProvider($model);

		$dp->setCriteria($criteria);


		$results = $dp->getData();

		$this->assertGreaterThan(0, count($results), 'That something was found');

		foreach ($results as $result)
		{
			/* @var $result ModelWithIndex */
			$index = $result->getIndex();
			codecept_debug($index);
			$this->assertNotEmpty($index);
			$this->assertIsString($index, 'That found result has set index');
		}
	}

	private function prepare()
	{
		$cities = [
			'New York',
			'Tokyo',
			'Los Angeles',
			'Paris',
			'Shanghai',
			'Shanghai',
			'Shanghai',
			'Shanghai',
			'Shanghai',
			'Shanghai',
			'Shanghai',
			'New Delhi',
			'New Hempshire'
		];

		foreach ($cities as $city)
		{
			$model = new ModelWithIndex();
			$model->_id = new MongoId;
			$model->title = $city;

			$im = new IndexManager($model);
			$im->index();
		}

		// Need to wait here, or it will fail...

		return $model;
	}

}
