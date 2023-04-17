<?php

namespace Search;

use function codecept_debug;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\Helpers\Debug\DebugFinder;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\Criteria\ModelWithFilterableFields;
use MongoDB\BSON\ObjectId as MongoId;

class PkTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before()
	{
	}

	protected function _after()
	{
	}

	// tests
	public function testFindByPk()
	{
		$model = new ModelWithFilterableFields;
		$model->_id = new MongoId;

		$saved = (new EntityManager($model))->save();
		$this->assertTrue($saved);

		$finder = new SearchFinder($model);

		$found = $finder->findByPk($model->_id);

		$params = DebugFinder::getFormattedParams($finder);

		codecept_debug($params);

		$this->assertNotEmpty($found);
	}

	public function testFindByPkAndRequiredId()
	{
		$model = new ModelWithFilterableFields;
		$model->_id = new MongoId;

		$saved = (new EntityManager($model))->save();
		$this->assertTrue($saved);

		$finder = new SearchFinder($model);

		$criteria = new Criteria;
		$criteria->addCond('_id', 'eq', $model->_id);

		$found = $finder->findByPk($model->_id, $criteria);

		$params = DebugFinder::getFormattedParams($finder);

		codecept_debug($params);

		$this->assertNotEmpty($found);
	}
}