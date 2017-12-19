<?php

namespace SearchFinder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Cursor;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\WithBaseAttributes;
use UnitTester;

class FindCursorTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindAllUsingCursor()
	{
		$model = new WithBaseAttributes();
		$model->string = 'foo';

		$em = new EntityManager($model);

		$em->insert();
		$indexed = (new IndexManager($model))->index();
		$this->assertTrue($indexed);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$indexed = (new IndexManager($model))->index();
		$this->assertTrue($indexed);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$indexed = (new IndexManager($model))->index();
		$this->assertTrue($indexed);

		$finder = new SearchFinder($model);
		$cursor = $finder->withCursor()->findAll();

		$this->assertSame(3, count($cursor));

		$this->assertInstanceOf(Cursor::class, $cursor);

		foreach ($cursor as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

}
