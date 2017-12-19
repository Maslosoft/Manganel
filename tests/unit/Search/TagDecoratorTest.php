<?php

namespace Search;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\Helpers\Debug\DebugSearchProvider;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField;
use Maslosoft\ManganelTest\Models\ModelWithBoostedFieldAndTags;
use MongoId;
use UnitTester;

class TagDecoratorTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function getModel()
	{
		return new ModelWithBoostedFieldAndTags;
	}

	protected function getTags()
	{

		return ['large', 'crowded', 'famous'];
	}

	protected function getTag()
	{
		return ['airport'];
	}

	protected function _before()
	{
		foreach (['New York', 'Newark', 'New Hampshire', 'Newag', 'Shanghai'] as $city)
		{
			$model =$this->getModel();
			$model->_id = new MongoId;


			$model->title = $city;
			if ($city === 'New York')
			{
				$model->tag = $this->getTags();
			}
			if ($city === 'Newark')
			{
				$model->tag = $this->getTag();
			}

			$model->description = 'Some famous cities';

			$im = new IndexManager($model);
			$indexed = $im->index();

			$this->assertNotEmpty($indexed, 'That document was indexed');

			$val = $im->get();
			$this->assertNotEmpty($val, 'That document is in index');
		}
	}

	public function testIfWillFindAllModelsWithTag()
	{
		$model =$this->getModel();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('[famous]');

		$dp->setCriteria($criteria);

		$results = $dp->getData();

		$this->showParams($dp);

		$this->assertNotEmpty($results);

		$this->assertCount(1, $results, 'That there was one result');

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThan(0, $score, 'That have some score');
		}
	}

	public function testIfWillFilterModelWithTag()
	{
		$model =$this->getModel();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('new [famous]');

		$dp->setCriteria($criteria);

		$results = $dp->getData();

		$this->showParams($dp);

		$this->assertNotEmpty($results);

		$this->assertCount(1, $results, 'That there was one result');

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThan(0, $score, 'That have some score');
		}
	}

	public function testIfWillFilterModelWithTwoTags()
	{
		$model =$this->getModel();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('new [famous][crowded]');

		$dp->setCriteria($criteria);

		$results = $dp->getData();

		$this->showParams($dp);

		$this->assertNotEmpty($results);

		$this->assertCount(1, $results, 'That there was one result');

		foreach ($results as $result)
		{
			/* @var $result ModelWithBoostedField */
			$score = $result->getScore();
			codecept_debug("Score: $score");
			$this->assertGreaterThan(0, $score, 'That have some score');
		}
	}

	public function testIfWillFilterModelWithTagWithNoMatch()
	{
		$model =$this->getModel();
		$dp = new SearchProvider($model);

		$criteria = new SearchCriteria(null, $model);
		$criteria->search('newark [famous]');

		$dp->setCriteria($criteria);

		$results = $dp->getData();

		$this->showParams($dp);

		$this->assertEmpty($results);

		$this->assertCount(0, $results, 'That there were no results');
	}

	private function showParams(SearchProvider $dp)
	{
		$params = DebugSearchProvider::getFormattedParams($dp);
		codecept_debug($params);
	}

}
