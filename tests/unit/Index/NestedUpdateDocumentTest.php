<?php

namespace Index;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\ManganelTest\Models\Nested\ModelWithEmbedArray;
use MongoId;
use UnitTester;

class NestedUpdateDocumentTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyUpdateDocumentWithNestedArrayWhenRemovingItem()
	{
		$id = new MongoId;
		$model = new ModelWithEmbedArray;
		$model->_id = $id;
		$model->title = 'maslosoft';

		$sub1 = new ModelWithEmbedArray;
		$sub1->title = 'sub1';
		$sub2 = new ModelWithEmbedArray;
		$sub2->title = 'sub1';

		$model->subNodes = [
			$sub1,
			$sub2
		];

		$im = new IndexManager($model);
		$indexed = $im->index();
		$this->assertTrue($indexed);

		$found = $im->get($id);
		/* @var $found ModelWithEmbedArray */
		$this->assertSame($model->title, $found->title);
		$this->assertCount(2, $found->subNodes);

		// Now test if will properly remove with index update

		unset($model->subNodes[1]);
		$indexed2 = $im->index();
		$this->assertTrue($indexed2);

		$found2 = $im->get($id);
		/* @var $found ModelWithEmbedArray */
		$this->assertSame($model->title, $found2->title);
		$this->assertCount(1, $found2->subNodes);
	}

}
