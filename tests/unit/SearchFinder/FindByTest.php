<?php

namespace SearchFinder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\WithBaseAttributes;
use UnitTester;

class FindByTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfCanFindByAttributes()
	{
		$model = new WithBaseAttributes();
		$model->int = 10;

		$em = new EntityManager($model);
		$em->insert();
		$indexed = (new IndexManager($model))->index();
		$this->assertTrue($indexed);

		$model = new WithBaseAttributes();
		$model->int = 20;
		$em->insert($model);
		$indexed = (new IndexManager($model))->index();
		$this->assertTrue($indexed);

		$finder = new SearchFinder($model);
		$found = $finder->findByAttributes([
			'int' => 10
		]);

		$this->assertNotNull($found, 'That document was indeed found');
		$this->assertInstanceof(WithBaseAttributes::class, $found);
		$this->assertSame(10, $found->int);
	}

}
