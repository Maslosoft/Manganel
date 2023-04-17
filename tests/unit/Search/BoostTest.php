<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField2;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField3;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;
use function codecept_debug;

class BoostTest extends Test
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

		// Second, but similar kind
		$model = new ModelWithBoostedField2();
		$model->_id = new MongoId;


		$model->title = 'New York, Tokyo, Los Angeles, Paris, Shanghai';
		$model->description = 'Some famous cities';

		$im = new IndexManager($model);
		$indexed = $im->index();

		$this->assertNotEmpty($indexed, 'That document was indexed');

		$val = $im->get();
		$this->assertNotEmpty($val, 'That document is in index');

		// Third, slightly different
		$model = new ModelWithBoostedField3();
		$model->_id = new MongoId;


		$model->keywords = 'city, large, famous';
		$model->title = 'New York, Tokyo, Los Angeles, Paris, Shanghai';
		$model->description = 'Some famous cities';

		$im = new IndexManager($model);
		$indexed = $im->index();

		$this->assertNotEmpty($indexed, 'That document was indexed');

		$val = $im->get();
		$this->assertNotEmpty($val, 'That document is in index');
	}

	// tests
	public function testIfWillCreateBoostParams(): void
	{
		$model = new ModelWithBoostedField();
		$q = new QueryBuilder($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('tokyo');
		$q->setCriteria($criteria);

		$params = $q->getParams();
		codecept_debug($params);

		$results = $q->search();
		$this->assertNotEmpty($results);
	}

	public function testIfWillFindModelWithBoostedFieldAndAdjustScore(): void
	{
		$models = [
			new ModelWithBoostedField,
		];
		$this->checkModels($models);
	}

	public function testIfWillProperlyUseBoostingOnDifferentModelsButSameFields(): void
	{
		$models = [
			new ModelWithBoostedField,
			new ModelWithBoostedField2,
		];
		$this->checkModels($models);
	}

	public function testIfWillProperlyUseBoostingOnDifferentModelsAlsoHavingDifferentFields(): void
	{
		$models = [
			new ModelWithBoostedField,
			new ModelWithBoostedField2,
			new ModelWithBoostedField3,
		];
		$this->checkModels($models);
	}

	private function checkModels(array $models): void
	{

		$dp = new SearchProvider($models);

		$criteria = new SearchCriteria(null, $models[0]);
		$criteria->search('tokyo');

		$dp->setCriteria($criteria);

		$results = $dp->getData();
		$this->assertNotEmpty($results);

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			/**
			 * NOTE: This value depends on ES version,
			 * but when greater this means that it's boosted.
			 */
			$this->assertGreaterThan(1, $score, 'That score is greater that boosted');
		}
	}
}
