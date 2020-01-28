<?php namespace Criteria;

use function assert;
use function codecept_debug;
use Codeception\Test\Unit;
use function json_encode;
use const JSON_PRETTY_PRINT;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\Criteria\ModelWithDate;
use UnitTester;
use const JSON_THROW_ON_ERROR;

class SelectTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
		$model = new ModelWithDate;
		$model->title = 'test';
		$model->number = 4;
		$saved = $model->save();
		$this->assertTrue($saved);
    }

    protected function _after()
    {
    }

    // tests
    public function testSelectingSomeFields()
    {
		$model = new ModelWithDate;
    	$criteria = new SearchCriteria(null, $model);
    	$criteria->select(['id', 'title']);

		$qb = (new QueryBuilder)->setCriteria($criteria);
		$params = $qb->getParams();
		codecept_debug(json_encode($params, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512));

		$finder = new SearchFinder([$model]);
		$found = $finder->find($criteria);

		$this->assertNotEmpty($found);
		assert($found instanceof ModelWithDate);
		$this->assertInstanceOf(ModelWithDate::class, $found);
		$this->assertSame(0, $found->number, 'Field `number` was not selected');


	}

	public function testSelectingAllFields()
	{
		$model = new ModelWithDate;
		codecept_debug('Select with number');

		$criteria = new SearchCriteria(null, $model);
		$criteria->select(['id', 'title', 'number']);

		$qb = (new QueryBuilder)->setCriteria($criteria);
		$params = $qb->getParams();
		codecept_debug(json_encode($params, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512));

		$finder = new SearchFinder([$model]);
		$found = $finder->find($criteria);

		$this->assertNotEmpty($found);
		assert($found instanceof ModelWithDate);
		$this->assertInstanceOf(ModelWithDate::class, $found);
		$this->assertSame(4, $found->number, 'Field `number` was selected');
	}

	public function testSelectingWithoutExplicitSelect()
	{
		$model = new ModelWithDate;
		codecept_debug('Select again without explicit select');

		$criteria = new SearchCriteria(null, $model);

		$qb = (new QueryBuilder)->setCriteria($criteria);
		$params = $qb->getParams();
		codecept_debug(json_encode($params, JSON_PRETTY_PRINT));

		$finder = new SearchFinder([$model]);
		$found = $finder->find($criteria);

		$this->assertNotEmpty($found);
		assert($found instanceof ModelWithDate);
		$this->assertInstanceOf(ModelWithDate::class, $found);
		$this->assertSame(4, $found->number, 'Field `number` was selected');
	}
}