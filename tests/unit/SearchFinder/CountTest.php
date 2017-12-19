<?php

namespace SearchFinder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\WithBaseAttributes;
use UnitTester;

class CountTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillCount()
	{

		$models = [
			new WithBaseAttributes(),
			new WithBaseAttributes(),
			new WithBaseAttributes()
		];

		foreach ($models as $model)
		{
			$em = new EntityManager($model);
			$this->assertTrue($em->insert());

			$im = new IndexManager($model);
			$this->assertTrue($im->index());
		}

		$finder = new SearchFinder($model);

		$count = $finder->count();

		$this->assertSame(3, $count);
	}

	public function testIfWillCountByCriteria()
	{
		$model = new WithBaseAttributes();
		$model->string = 'foo';

		$em = new EntityManager($model);

		$em->insert();
		(new IndexManager($model))->index();

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		(new IndexManager($model))->index();

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		(new IndexManager($model))->index();

		// Some other models
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		(new IndexManager($model))->index();

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		(new IndexManager($model))->index();

		$finder = new SearchFinder($model);

		$count = $finder->count();

		$this->assertSame(5, $count);

		$criteria = new Criteria();

		$criteria->addCond('string', '==', 'foo');

		$criteriaCount = $finder->count($criteria);

		$this->assertSame(3, $criteriaCount);
	}

	public function testIfWillCountByAttributes()
	{
		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em = new EntityManager($model);
		$em->insert();

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		// Some other models

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$finder = new SearchFinder($model);

		$count = $finder->count();

		$this->assertSame(5, $count);

		$attributesCount = $finder->countByAttributes([
			'string' => 'foo'
		]);

		$this->assertSame(3, $attributesCount);
	}

}
