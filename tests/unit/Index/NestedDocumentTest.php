<?php

namespace Index;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Model\Image;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\Receiver;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\ManganelTest\Models\NestedModel;
use MongoId;
use UnitTester;

class NestedDocumentTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfCanStoreNestedDocument()
	{
		$model = new NestedModel();
		$model->_id = new MongoId;
		$model->username = 'maslosoft';

		$image = new Image;
		$image->_id = new MongoId;
		$image->filename = 'my-photo.jpg';
		$model->avatar = $image;

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$params = [
			'index' => $mnl->index,
			'type' => TypeNamer::nameType($model),
			'id' => (string) $model->_id,
			'body' => SearchArray::fromModel($model)
		];

		codecept_debug($params);

		$client->index($params);

		$get = $params;
		unset($get['body']);
		$found = $client->get($get)['_source'];
		codecept_debug($found);
		$this->assertSame($model->username, $found['username']);
	}

	public function testIfWillStoreNestedDocumentFromMockSignal()
	{
		$model = new NestedModel();
		$model->_id = new MongoId;
		codecept_debug((string) $model->_id);
		$model->username = 'maslosoft';

		$image = new Image;
		$image->_id = new MongoId;
		$image->filename = 'my-photo.jpg';
		$model->avatar = $image;

		// Mock signal receive
		(new Receiver())->onSave(new AfterSave($model));

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => TypeNamer::nameType($model),
			'id' => (string) $model->_id,
		];
		$found = $client->get($get)['_source'];
		codecept_debug($found);
		$this->assertSame($model->username, $found['username']);
	}

}
