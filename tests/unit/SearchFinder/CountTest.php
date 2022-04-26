<?php

namespace SearchFinder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\WithBaseAttributes;
use Maslosoft\ManganelTest\Models\WithBaseAttributesSecond;
use Maslosoft\ManganelTest\Models\WithBaseAttributesThird;
use MongoId;
use UnitTester;
use function codecept_debug;

class CountTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillCount(): void
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

	public function testIfWillCountOnlyOneType(): void
	{
		$models = [
			new WithBaseAttributesSecond(),
			new WithBaseAttributesSecond(),
			new WithBaseAttributesSecond(),
			new WithBaseAttributes(),
			new WithBaseAttributes()
		];

		$ids = [
			new MongoId('6267fee5bf858d94cd0e65c5'),
			new MongoId('6267fee5bf858d94cd0e65c6'),
			new MongoId('6267fee5bf858d94cd0e65c7'),
			new MongoId('6267fee5bf858d94cd0e65c8'),
			new MongoId('6267fee5bf858d94cd0e65c9'),
		];

		codecept_debug((string)new MongoId);

		foreach ($models as $i => $model)
		{
			$model->_id = $ids[$i];
			$model->int = $i;
			$em = new EntityManager($model);
			$this->assertTrue($em->insert());

			$im = new IndexManager($model);
			$this->assertTrue($im->index());
		}

		$model = new WithBaseAttributes();
		$finder = new SearchFinder($models[4]);

		$count = $finder->count();

		$this->assertSame(2, $count, 'Expected 2 instances of `WithBaseAttributes` when not using search, ie when using match_all');
	}

	public function testIfWillCountByCriteria(): void
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

	public function testIfWillCountByAttributes(): void
	{
		$model = new WithBaseAttributesSecond();
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

		$finder = new SearchFinder([new WithBaseAttributes, new WithBaseAttributesSecond]);

		$count = $finder->count();

		$this->assertSame(5, $count);

		$attributesCount = $finder->countByAttributes([
			'string' => 'foo'
		]);

		$this->assertSame(3, $attributesCount, 'Expected 3 objects having `foo` of type `WithBaseAttributes` and `WithBaseAttributesSecond`');
	}

	public function testIfWillCountByAttributesWithTwoDifferentModels(): void
	{
		$model = new WithBaseAttributesSecond();
		$model->string = 'foo';
		$em = new EntityManager($model);
		$em->insert();

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$model = new WithBaseAttributesThird();
		$model->string = 'foo';
		$em->insert($model);

		// Some other models

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$finder = new SearchFinder(
			[new WithBaseAttributes(), new WithBaseAttributesThird()]
		);

		$count = $finder->count();

		$this->assertSame(4, $count);

		$attributesCount = $finder->countByAttributes([
			'string' => 'foo'
		]);

		$this->assertSame(2, $attributesCount, 'Expected 2 objects having `foo` of type `WithBaseAttributes` and `WithBaseAttributesThird`');
	}
}
