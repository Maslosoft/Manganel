<?php
namespace Criteria;

use Maslosoft\Cache\Cache;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\Sanitizers\DateWriteEsSanitizer;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchCriteriaArray;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\Criteria\ModelWithDate as m;
use MongoDate;

class DateTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    private $time = 0;

    protected function _before()
    {
		// Ensure equal time for all tests
		$this->time = time();

    	$this->makeData();
    }

    protected function _after()
    {
    }

    public function testManganSanitizersMap()
	{
		$m = Mangan::fly();
		codecept_debug($m->connectionId);

		$map = $m->sanitizersMap;
		codecept_debug($map);

		$d = $m->decorators;

		$this->assertArrayHasKey(SearchCriteriaArray::class, $map);
		$this->assertArrayHasKey(SearchCriteriaArray::class, $d);
	}

    public function testIfWillFilterByDateRange()
    {
		$dp = new SearchProvider(new m);
		$count = $dp->getItemCount();

		$this->assertSame(10, $count, 'Data is properly set up');

    	$criteria = new SearchCriteria(null, new m);
    	$criteria->addCond('date', '>', new MongoDate($this->time + Cache::Day * -2));
		$criteria->addCond('date', '<=', new MongoDate($this->time + Cache::Day * 2));

		$criteria->search('maslosoft');

		$qb = (new QueryBuilder)->setCriteria($criteria);
		$params = $qb->getParams();
//		codecept_debug(json_encode($params, JSON_PRETTY_PRINT));
		codecept_debug(json_encode($params['body']['query']['bool']['filter'], JSON_PRETTY_PRINT));

		$dp = new SearchProvider(new m);
		$dp->setCriteria($criteria);
		$count = $dp->getItemCount();

		$this->assertSame(3, $count);
    }

	private function makeData()
	{
		$data = [];
		// First value is status, second is code
		$data[] = new MongoDate($this->time + (Cache::Day * -5));
		$data[] = new MongoDate($this->time + (Cache::Day * -4));
		$data[] = new MongoDate($this->time + (Cache::Day * -3));
		$data[] = new MongoDate($this->time + (Cache::Day * -2));
		$data[] = new MongoDate($this->time + (Cache::Day * -1));
		$data[] = new MongoDate($this->time + (Cache::Day * 1));
		$data[] = new MongoDate($this->time + (Cache::Day * 2));
		$data[] = new MongoDate($this->time + (Cache::Day * 3));
		$data[] = new MongoDate($this->time + (Cache::Day * 4));
		$data[] = new MongoDate($this->time + (Cache::Day * 5));

		foreach ($data as $date)
		{
			$m = new m;
			$m->date = $date;
			$m->title = 'Maslosoft';
			codecept_debug((new DateWriteEsSanitizer)->write($m, $m->date));
			$saved = (new EntityManager($m))->save();
			$this->assertTrue($saved);
		}
	}
}