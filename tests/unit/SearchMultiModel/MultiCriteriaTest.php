<?php

namespace SearchMultiModel;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\ManganelTest\Models\Criteria\With\SimpleDocumentWithActive;
use Maslosoft\ManganelTest\Models\Criteria\With\SimpleDocumentWithStatus;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class MultiCriteriaTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillCountAllFilteredDocuments()
	{

		$q = $this->prepare();

//		$conds = $q->getCriteria()->getConditions();
//		codecept_debug($conds);
		$params = $q->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		/* @var $result SimpleModel[] */
		$result = $q->count();
		codecept_debug($result);
		$this->assertSame(2, $result, 'That only documents by attached criteria are included');
	}

	public function testIfWillCountFilteredDocumentsByQueryString()
	{

		$q = $this->prepare();

		$params = $q->getParams('sh');
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		/* @var $result SimpleModel[] */
		$result = $q->count('new');
		codecept_debug($result);
		$this->assertSame(1, $result, 'That only filtered resuls has been counted');
		unset($result);
	}

	/**
	 *
	 * @return QueryBuilder
	 */
	private function prepare()
	{
		$model1 = new SimpleDocumentWithActive;
		$model1->_id = new MongoId;
		$model1->active = true;
		$model1->title = 'New York';
		$criteria1 = new Criteria;
		$criteria1->addCond('active', '==', true);
		$model1->setDbCriteria($criteria1);

		$model11 = new SimpleDocumentWithActive;
		$model11->_id = new MongoId;
		$model11->active = false;
		$model11->title = 'New Shanghai';

		$model2 = new SimpleDocumentWithStatus;
		$model2->_id = new MongoId;
		$model2->status = SimpleDocumentWithStatus::StatusUsed;
		$model2->name = 'BMW';
		$criteria2 = new Criteria;
		$criteria2->addCond('status', '==', SimpleDocumentWithStatus::StatusUsed);
		$model2->setDbCriteria($criteria2);

		$model22 = new SimpleDocumentWithStatus;
		$model22->_id = new MongoId;
		$model22->status = SimpleDocumentWithStatus::StatusNew;
		$model22->name = 'New Mercedes';

		$all = [
			$model1,
			$model11,
			$model2,
			$model22
		];

		foreach ($all as $toIndex)
		{
			$im = new IndexManager($toIndex);
			$indexed = $im->index();
			$this->assertTrue($indexed, 'That document was indexded');
		}
		$q = new QueryBuilder();
		$q->add([$model1, $model2]);
		return $q;
	}

}
