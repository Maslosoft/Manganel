<?php

namespace Index;

use Codeception\TestCase\Test;
use Maslosoft\Manganel\Filters\SearchFilter;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\Meta\ManganelMeta;
use Maslosoft\ManganelTest\Models\ExplicitlyNonIndexableField;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SearchFilterTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillFilter()
	{
		$model = new ExplicitlyNonIndexableField();
		$filter = new SearchFilter();

		$allowed = $filter->fromModel($model, ManganelMeta::create($model)->field('password'));

		$this->assertFalse($allowed, 'That password field was not indexed');

		$allowed = $filter->fromModel($model, ManganelMeta::create($model)->field('details'));

		$this->assertFalse($allowed, 'That details field was not indexed');
	}

	public function testIfWillSkipNonSearchableField()
	{
		$model = new ExplicitlyNonIndexableField();
		$model->_id = new MongoId();
		$model->title = 'Jersey';
		$model->password = 'my secret';
		$model->details = 'some secret details';
		$im = new IndexManager($model);

		$im->index();

		$found = $im->get();

		/* @var $found ExplicitlyNonIndexableField */
		codecept_debug($found);

		$this->assertInstanceOf(ExplicitlyNonIndexableField::class, $found);
		$this->assertSame($model->title, $found->title);

		codecept_debug($found->password);
		$this->assertTrue(empty($found->password), 'That password was not indexed');

		codecept_debug($found->details);
		$this->assertTrue(empty($found->details), 'That details field was not indexed');
	}

}
