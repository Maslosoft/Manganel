<?php

namespace Index;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Helpers\PropertyFilter\Filter;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Manganel\Decorators\UnderscoreIdFieldDecorator;
use Maslosoft\Manganel\Filters\SearchFilter;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\ManganelTest\Models\ExplicitlyNonIndexableField;
use Maslosoft\ManganelTest\Models\ModelWithDate;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDate;
use MongoId;
use UnitTester;

class SearchArrayTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfManganHasProperlyConfiguredFilters()
	{
		$model = new ExplicitlyNonIndexableField;
		// Check if mangan has properly configured filters
		$mangan = Mangan::fromModel($model);
		$filterConfig = $mangan->filters;

		$this->assertTrue(array_key_exists(SearchArray::class, $filterConfig), 'That SearchArray filters are configured');
		$this->assertTrue(in_array(SearchFilter::class, $filterConfig[SearchArray::class]), 'That SearchFilter is configured');
	}

	public function testIfFiltersAreProperlySetUpOnModel()
	{
		$model = new ExplicitlyNonIndexableField;

		$meta = ManganMeta::create($model);
		$passwordMeta = $meta->field('password');

		// Check if filters are setup as required
		$this->assertNotEmpty($passwordMeta->secret);
		$this->assertTrue($passwordMeta->secret->secret);
	}

	public function testIfWillSkipNonIndexableFields()
	{
		$model = new ExplicitlyNonIndexableField;
		$model->title = 'blah';
		$model->password = 'secret password';
		$model->details = 'secret details';
		$model->garbage = 'some non persistend garbadge';


		$meta = ManganMeta::create($model);
		$filter = new Filter($model, SearchArray::class, $meta);
		$passwordFilter = $filter->getFor('password');


		$data = SearchArray::fromModel($model);

		codecept_debug($data);

		$this->assertTrue(array_key_exists('title', $data), 'That title *is* stored');
		$this->assertSame('blah', $data['title']);

		$this->assertFalse(array_key_exists('password', $data), 'That password is *not* stored');
		$this->assertFalse(array_key_exists('details', $data), 'That details is *not* stored');
		$this->assertFalse(array_key_exists('garbage', $data), 'That garbage is *not* stored');
	}

	public function testIfWillProperlyHandleMongoId()
	{
		$key = UnderscoreIdFieldDecorator::Key;
		$model = new SimpleModel();
		$model->_id = new MongoId();
		$arr = SearchArray::fromModel($model);
		codecept_debug($arr);
		$this->assertInternalType('string', $arr[$key]);

		$fromArray = SearchArray::toModel($arr);
		/* @var $fromArray SimpleModel */
		codecept_debug($model->_id);
		codecept_debug($fromArray->_id);
		$this->assertInstanceOf(MongoId::class, $model->_id);
		$this->assertInstanceOf(MongoId::class, $fromArray->_id);
		$this->assertSame((string) $model->_id, (string) $fromArray->_id);
	}

	public function testIfWillProperlyHandleMongoDate()
	{
		$model = new ModelWithDate();
		$model->createdAt = new MongoDate();

		$arr = SearchArray::fromModel($model);
		codecept_debug($arr['createdAt']);
		$this->assertInternalType('int', $arr['createdAt']);

		$fromArray = SearchArray::toModel($arr);
		/* @var $fromArray ModelWithDate */
		codecept_debug(date('c', $model->createdAt->sec));
		$this->assertInstanceOf(MongoDate::class, $model->createdAt);
		$this->assertInstanceOf(MongoDate::class, $fromArray->createdAt);
		$this->assertSame((int) $model->createdAt->sec, (int) $fromArray->createdAt->sec);
	}

}
