<?php

namespace SearchProvider;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Pagination;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\SimpleModel;
use Maslosoft\ManganelTest\Models\SimpleModelSecond;
use Maslosoft\ManganelTest\Models\SimpleModelThird;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class MultiModelTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindDocumentsAndCreateItsInstancesWithSearchProvider(): void
	{
		$model = $this->prepare();

		$criteria = new SearchCriteria();
		$criteria->search('tokyo');

		$dp = new SearchProvider($model);

		$dp->setCriteria($criteria);

		$this->assertSame(3, $dp->getTotalItemCount(), 'That total is 1 item of each type');
		$this->assertSame(3, $dp->getItemCount(), 'That current result set has 1 item of each type');

		$results = $dp->getData();

		$this->assertCount(3, $results, 'That one result was found');

		$this->assertArrayHasKey(0, $results, 'That results starts with key 0');

		$result = $results[0];

		$this->assertInstanceOf(AnnotatedInterface::class, $result);
	}

	public function testIfWillFindProperlyCountWithSearchProviderPagination(): void
	{
		$models = $this->prepare();

		$criteria = new SearchCriteria();
		$criteria->search('shanghai');

		$pagination = new Pagination();
		$pagination->setSize(2);

		$dp = new SearchProvider($models);
		$dp->setPagination($pagination);
		$dp->setCriteria($criteria);

		$this->assertSame(21, $dp->getTotalItemCount(), 'That total is 21 items');
		$this->assertSame(2, $dp->getItemCount(), 'That current result set has 2 items');

		$results = $dp->getData();

		$this->assertCount(2, $results, 'That 2 results was returned');

		$this->assertArrayHasKey(0, $results, 'That results starts with key 0');

		foreach ($results as $i => $result)
		{
			codecept_debug(get_class($result));
			$this->assertInstanceOf(AnnotatedInterface::class, $result, "That result $i has proper type");
			$this->assertSame('Shanghai', $result->title, "That title is properly populated");
		}
	}

	private function prepare(): array
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
			'New Hampshire'
		];

		foreach ($cities as $city)
		{
			$models = [
				new SimpleModel,
				new SimpleModelSecond,
				new SimpleModelThird
			];
			foreach ($models as $model)
			{
				$model->_id = new MongoId;
				$model->title = $city;
				$im = new IndexManager($model);
				$im->index();
			}
		}

		return $models;
	}

}
