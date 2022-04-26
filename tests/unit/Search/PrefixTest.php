<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField;
use MongoId;
use UnitTester;
use function version_compare;
use const ES_VERSION;

class PrefixTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before(): void
	{
		$model = new ModelWithBoostedField();
		$model->_id = new MongoId;


		$model->title = 'New York, Tokyo, Los Angeles, Paris, Shanghai';
		$model->description = 'Some famous cities';

		$im = new IndexManager($model);
		$indexed = $im->index();

		$this->assertNotEmpty($indexed, 'That document was indexed');

		$val = $im->get();
		$this->assertNotEmpty($val, 'That document is in index');
	}

	public function testIfWillFindModelWithPartialString(): void
	{
		$model = new ModelWithBoostedField();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('tok');

		$dp->setCriteria($criteria);

		$results = $dp->getData();
		$this->assertNotEmpty($results);

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThanOrEqual(1, $score, 'That have some score');
		}
	}

	public function testIfWillFindModelWithPartialStringAndUserAddedWildcard(): void
	{
		$model = new ModelWithBoostedField();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('tok*');

		$dp->setCriteria($criteria);

		$results = $dp->getData();
		$this->assertNotEmpty($results);

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThanOrEqual(1, $score, 'That have some score');
		}
	}

	public function testIfWillFindModelWithFullStringEndingWithSpace(): void
	{
		// Prepare a bit different data
		foreach (['mangan', 'manganel'] as $title)
		{
			$model = new ModelWithBoostedField();
			$model->_id = new MongoId;


			$model->title = $title;
			$model->description = 'Some famous Maslosoft projects';

			$im = new IndexManager($model);
			$indexed = $im->index();

			$this->assertNotEmpty($indexed, 'That document was indexed');

			$val = $im->get();
			$this->assertNotEmpty($val, 'That document is in index');
		}

		// Tests

		$model = new ModelWithBoostedField();
		$dp = new SearchProvider($model);

		// There should be two results for string without space
		$criteria = new SearchCriteria(null, $model);
		$criteria->search('mangan');

		$dp->setCriteria($criteria);

		$results = $dp->getData(true);
		$this->assertNotEmpty($results);

		codecept_debug('NOTE: This returns 3 in ES 5 (should 2), but it matches `_class` field. Line: ' . (__LINE__ + 2));
		codecept_debug('See: https://github.com/Maslosoft/Manganel/issues/14');
		if(version_compare(ES_VERSION, '6', '<'))
		{
			$this->assertCount(2, $results);
		}
		else
		{
			$this->assertCount(2, $results);
		}
		// Search with space
		$criteria = new SearchCriteria(null, $model);
		$criteria->search('mangan ');

		$dp->setCriteria($criteria);

		$results = $dp->getData(true);
		$this->assertNotEmpty($results);

		$this->assertCount(1, $results);

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThan(1, $score, 'That have some score');
		}
	}

}
