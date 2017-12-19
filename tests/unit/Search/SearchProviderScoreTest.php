<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\ModelWithScore;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoId;
use UnitTester;

class SearchProviderScoreTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindDocumentsAndSetItsScore()
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
			/* @var $result ModelWithScore */
			$score = $result->getScore();
			$maxScore = $result->getMaxScore();
			codecept_debug("Score: $score/$maxScore");
			$this->assertGreaterThan(0, $score, 'That found result has some score');
			$this->assertGreaterThan(0, $maxScore, 'That found result has some max score');
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
			$model = new ModelWithScore();
			$model->_id = new MongoId;
			$model->title = $city;

			$im = new IndexManager($model);
			$im->index();
		}

		// Need to wait here, or it will fail...

		return $model;
	}

}
