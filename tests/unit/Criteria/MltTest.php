<?php
namespace Criteria;

use function codecept_debug;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\Options\MoreLike;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoId;

class MltTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGeneratingMltQuery()
    {
    	$model = $this->makeData();

    	$criteria = new SearchCriteria(null, $model);
    	$like = new MoreLike($model);
    	$like->minDocFreq = 1;
    	$criteria->moreLike($like);
		$params = (new QueryBuilder)->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_PRETTY_PRINT));

		$dp = new SearchProvider(SimpleModel::class);

		$dp->setCriteria($criteria);

		$result = $dp->getData();

		$this->assertArrayHasKey(0, $result);
		$this->assertNotSame((string) $result[0]->_id, (string) $model->_id);
		$this->assertCount(1, $result);
    }

    private function makeData()
	{
		$model = new SimpleModel;
		$model->_id = new MongoId('5b23eb6da3d24b9b6e772cc5');
		$model->title = 'New New new York';
		(new EntityManager($model))->save();

		$model2 = new SimpleModel;
		$model2->_id = new MongoId('5b23ecb9a3d24b8c70261379');

		$model2->title = 'New New new';
		(new EntityManager($model2))->save();

		return $model;
	}
}