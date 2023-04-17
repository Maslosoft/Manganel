<?php
namespace Criteria;

use Codeception\Test\Unit;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField;
use Maslosoft\ManganelTest\Models\ModelWithBoostedField2;
use UnitTester;
use function codecept_debug;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Manganel\Options\MoreLike;
use Maslosoft\Manganel\QueryBuilder;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\SearchProvider;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDB\BSON\ObjectId as MongoId;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class MltTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    // tests
    public function testGeneratingMltQuery(): void
    {
		$this->checkGeneratingMltQueryFor([new SimpleModel]);
    }

	public function testGeneratingMltQueryWithMultiModelsAndBoostedFields(): void
	{
		$this->checkGeneratingMltQueryFor([new SimpleModel, new ModelWithBoostedField, new ModelWithBoostedField2]);
	}

	private function checkGeneratingMltQueryFor(array $models): void
	{
		$model = $this->makeData();

		$criteria = new SearchCriteria(null, $model);
		$criteria->setModels($models);
		$like = new MoreLike($model);
		$like->minDocFreq = 1;
		$criteria->moreLike($like);
		$params = (new QueryBuilder)->setCriteria($criteria)->getParams();
		codecept_debug(json_encode($params['body'], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

		$dp = new SearchProvider($models);

		$dp->setCriteria($criteria);

		$result = $dp->getData();

		$this->assertNotEmpty($result);
		$this->assertArrayHasKey(0, $result);
		$this->assertNotSame((string) $result[0]->_id, (string) $model->_id);
		$this->assertCount(1, $result);
	}

    private function makeData(): SimpleModel
	{
		$model = new SimpleModel;
		$model->_id = new MongoId('5b23eb6da3d24b9b6e772cc5');
		$model->title = 'New New new York';
		(new EntityManager($model))->save();

		$model2 = new SimpleModel;
		$model2->_id = new MongoId('5b23ecb9a3d24b8c70261379');

		$model2->title = 'New New new';
		(new EntityManager($model2))->save();

		$model3 = new ModelWithBoostedField;
		$model3->_id = new MongoId('5b23ecb9a3d24b8c70261380');

		$model3->title = 'Tokyo';
		(new EntityManager($model3))->save();

		$model4 = new ModelWithBoostedField2;
		$model4->_id = new MongoId('5b23ecb9a3d24b8c70261381');

		$model4->title = 'Tokyo';
		(new EntityManager($model4))->save();

		return $model;
	}
}