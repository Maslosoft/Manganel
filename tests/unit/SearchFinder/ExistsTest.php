<?php

namespace SearchFinder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\Helpers\Debug\DebugFinder;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\CompositePrimaryKey;
use Maslosoft\ManganelTest\Models\ModelWithI18N;
use Maslosoft\ManganelTest\Models\WithBaseAttributes;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ExistsTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfExistsAnything()
	{
		$model = new WithBaseAttributes();
		$finder = new SearchFinder($model);

		$this->assertFalse($finder->exists());

		$model->int = 10;

		$em = new EntityManager($model);
		$em->insert();
		(new IndexManager($model))->index();

		$model = new WithBaseAttributes();
		$model->int = 20;
		$em->insert($model);
		(new IndexManager($model))->index();

		$this->assertTrue($finder->exists());
	}

	public function testIfExistsByCriteria()
	{
		$model = new WithBaseAttributes();
		$model->int = 10;

		$em = new EntityManager($model);
		$em->insert();
		(new IndexManager($model))->index();

		$model = new WithBaseAttributes();
		$model->int = 20;
		$em->insert($model);
		(new IndexManager($model))->index();

		$finder = new SearchFinder($model);

		$criteria = new Criteria();
		$criteria->int = 10;

		$this->assertTrue($finder->exists($criteria));

		$criteria = new Criteria();
		$criteria->int = 100;

		$this->assertFalse($finder->exists($criteria));
	}

	public function testIfExistsAnythingWithCompositePk()
	{
		$id = new MongoId;
		$model = new CompositePrimaryKey;
		$model->primaryOne = $id;
		$model->primaryTwo = 2;
		$model->primaryThree = 3;

		$finder = new SearchFinder($model);
		$em = new EntityManager($model);

		$existsNone = $finder->exists();

		$this->assertFalse($existsNone, 'That none of document was found');

		// Now save some
		$saved = $em->insert();
		$indexed = (new IndexManager($model))->index();

		$this->assertTrue($indexed, 'That document was indexed');
		$this->assertTrue($saved, 'That document was saved');


		$existsAny = $finder->exists();

		$this->assertTrue($existsAny, 'That any document was found');
	}

	public function testIfExistsByCriteriaWithCompositePk()
	{
		$id = new MongoId;
		$model = new CompositePrimaryKey;
		$model->primaryOne = $id;
		$model->primaryTwo = 2;
		$model->primaryThree = 3;

		$finder = new SearchFinder($model);
		$em = new EntityManager($model);
		$saved = $em->insert();
		$indexed = (new IndexManager($model))->index();

		$this->assertTrue($indexed, 'That document was indexed');
		$this->assertTrue($saved, 'That document was saved');

		$criteria = new Criteria();
		$criteria->primaryOne = $id;
		$criteria->primaryTwo = 2;
		$criteria->primaryThree = 3;

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$exists = $finder->exists($criteria);

		$this->assertTrue($exists, 'That document by criteria was found');

		$criteriaNot = new Criteria();
		$criteriaNot->primaryOne = new MongoId;
		$criteriaNot->primaryTwo = 2;
		$criteriaNot->primaryThree = 3;

		/**
		 * TODO Query is not build properly
		 */
		codecept_debug('Query is not build properly as it not contains primaryOne');
		$notExists = $finder->exists($criteriaNot);
		codecept_debug(DebugFinder::getFormattedParams($finder));

		$this->assertFalse($notExists, 'That document was not found');
	}

	public function testIfExistsByCriteriaI18N()
	{
		$langs = [
			'en',
			'pl'
		];

		$model = new ModelWithI18N();

		$model->setLanguages($langs);

		$model->title = 'foot';

		$finder = new SearchFinder($model);

		$criteria = new Criteria();
		$criteria->title = 'foot';

		// Should not exists
		$this->assertFalse($finder->exists($criteria));

		(new EntityManager($model))->insert();

		// Should exists
		$this->assertTrue($finder->exists($criteria));
	}

}
