<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Pagination;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoId;
use UnitTester;

class SearchProviderTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindDocumentsAndCreateItsInstancesWithSearchProvider()
	{
		$model = $this->prepare();

		$criteria = new SearchCriteria();
		$criteria->search('tokyo');

		$dp = new SearchProvider($model);

		$dp->setCriteria($criteria);

		$this->assertSame(1, $dp->getTotalItemCount(), 'That total is 1 item');
		$this->assertSame(1, $dp->getItemCount(), 'That current result set has 1 item');

		$results = $dp->getData();

		$this->assertSame(1, count($results), 'That one result was found');

		$this->assertTrue(array_key_exists(0, $results), 'That results starts with key 0');

		$result = $results[0];

		$this->assertInstanceOf(SimpleModel::class, $result);
	}

	public function testIfWillFindProperlyCountWithSearchProviderPagination()
	{
		$model = $this->prepare();

		$criteria = new SearchCriteria();
		$criteria->search('shanghai');

		$pagination = new Pagination();
		$pagination->setSize(2);

		$dp = new SearchProvider($model);
		$dp->setPagination($pagination);
		$dp->setCriteria($criteria);

		$this->assertSame(7, $dp->getTotalItemCount(), 'That total is 7 items');
		$this->assertSame(2, $dp->getItemCount(), 'That current result set has 2 items');

		$results = $dp->getData();

		$this->assertSame(2, count($results), 'That 2 result was returned');

		$this->assertTrue(array_key_exists(0, $results), 'That results starts with key 0');

		foreach ($results as $i => $result)
		{
			$this->assertInstanceOf(SimpleModel::class, $result, "That result $i has proper type");
			$this->assertSame('Shanghai', $result->title, "That title is properly populated");
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
