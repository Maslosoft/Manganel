<?php namespace Nested;

use Codeception\Test\Unit;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\Manganel\SearchFinder;
use Maslosoft\ManganelTest\Models\Nested\DbRefs\AssetCollection;
use Maslosoft\ManganelTest\Models\Nested\DbRefs\AssetGroup;
use UnitTester;
use function codecept_debug;
use function file_get_contents;
use function file_put_contents;

class DbRefsTest extends Unit
{
	private const ModelFilename = __DIR__ . '/data/DbRefsTest.model.php';
    /**
     * @var UnitTester
     */
    protected $tester;

	/**
	 * @var AssetCollection
	 */
    private $model;

    protected function _before()
    {
    	$group = new AssetGroup;
    	$group->title = 'Group Title';
    	$model = new AssetCollection;
    	$model->title = 'Collection Title';
    	$model->items = [
    		$group
		];
    	$saved = $model->save();
    	$this->assertTrue($saved, 'Model was saved');
    	$this->model = $model;
    }

    protected function _after()
    {
    }

    // tests

	public function testConvertingModelWithDbRefsToArray()
	{
		$data = SearchArray::fromModel($this->model);

		$this->assertArrayHasKey('_mongo_id_', $data);
		$this->assertArrayHasKey('items', $data);
		$this->assertArrayHasKey(0, $data['items']);
		codecept_debug(self::ModelFilename);
		codecept_debug(PhpExporter::export($data));
	}

	public function testConvertingModelWithDbRefsFromArray()
	{
		$data = require self::ModelFilename;
		$model = SearchArray::toModel($data);

		$this->assertInstanceOf(AssetCollection::class, $model);
	}

    public function testFindingModelWithDbRefs()
    {
    	$finder = new SearchFinder(new AssetCollection);
    	$results = $finder->findAll();
		$this->assertCount(1, $results);
    }
}