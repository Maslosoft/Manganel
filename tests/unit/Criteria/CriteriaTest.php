<?php

namespace Criteria;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\Criteria\ModelWithFilterableFields as m;
use UnitTester;

class CriteriaTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillFilterByCode()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('code', '==', m::CodeCritical);

		$shouldCount = $nums[m::CodeCritical];

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);
		$params = (new QueryBuilder())->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));
		$totalCount = $dp->getTotalItemCount();

		$conds = $dp->getCriteria()->getConditions();

		$data = $dp->getData();

		$count = $dp->getItemCount();
		$this->assertCount($count, $data);
		$this->assertSame($shouldCount, $count, sprintf('That current result set has %s items', $shouldCount));
	}

	public function testIfWillFilterByCodeAndStatus()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('code', '==', m::CodeCritical);
		$criteria->addCond('status', '==', m::StatusActive);

		$shouldCount = $nums[m::StatusActive . '-' . m::CodeCritical];

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$this->assertSame($shouldCount, $dp->getItemCount(), sprintf('That current result set has %s items', $shouldCount));
	}


	public function testIfWillFilterByNotCodeAndNotStatus()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('code', '!=', m::CodeCritical);
		$criteria->addCond('status', '!=', m::StatusActive);

		$shouldCount = 4;

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$params = (new QueryBuilder())->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		$this->assertSame($shouldCount, $dp->getItemCount(), sprintf('That current result set has %s items', $shouldCount));
	}

	public function testIfWillFilterByStatusAndSearch()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('status', '==', m::StatusInactive);
		$criteria->search('Vienna');

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$this->assertSame(2, $dp->getItemCount(), 'That current result set has items');
	}

	public function testIfWillFilterByGteLteStatusAndSearch()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('status', '>=', m::StatusInactive);
		$criteria->addCond('status', '<', m::StatusBanned);
		$criteria->search('Vienna');

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$params = (new QueryBuilder())->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		$this->assertSame(2, $dp->getItemCount(), 'That current result set has items');
	}

	public function testIfWillFilterByGteStatusAndSearch()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('status', '>=', m::StatusInactive);
		$criteria->search('Vienna');

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$params = (new QueryBuilder())->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		$this->assertSame(2, $dp->getItemCount(), 'That current result set has items');
	}

	public function testIfWillFilterByNotStatusAndSearch()
	{
		$nums = $this->makeData();

		$criteria = new SearchCriteria();
		$criteria->addCond('status', '!=', m::StatusInactive);
		$criteria->search('Vienna');

		$dp = new SearchProvider(m::class);

		$dp->setCriteria($criteria);

		$params = (new QueryBuilder())->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		$this->assertSame(1, $dp->getItemCount(), 'That current result set has items');
	}

	private function makeData()
	{
		$data = [];
		// First value is status, second is code
		$data[] = [m::StatusActive, m::CodeCritical, 'Tokyo'];
		$data[] = [m::StatusActive, m::CodeNotice, 'Tokyo'];
		$data[] = [m::StatusBanned, m::CodeImportant, 'Amsterdam'];
		$data[] = [m::StatusInactive, m::CodeInfo, 'Amsterdam'];
		$data[] = [m::StatusBanned, m::CodeInfo, 'Amsterdam'];
		$data[] = [m::StatusInactive, m::CodeCritical, 'Vienna'];
		$data[] = [m::StatusInactive, m::CodeImportant, 'Vienna'];
		$data[] = [m::StatusActive, m::CodeCritical, 'Vienna'];

		// Number of particular types
		$nums = [
		];

		foreach ($data as $row)
		{
			$m = new m;
			$m->status = array_shift($row);
			$m->code = array_shift($row);
			$m->title = array_shift($row);

			@$nums[$m->status] ++;
			@$nums[$m->code] ++;
			@$nums[$m->status . '-' . $m->code] ++;

			$saved = (new EntityManager($m))->save();
			$this->assertTrue($saved);
		}
//		codecept_debug($nums);
		ksort($nums);

		// Ensure that index is updated

		return $nums;
	}

}
