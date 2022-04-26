<?php

namespace Index;

use Codeception\TestCase\Test;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Mangan\Model\Trash;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\Receiver;
use Maslosoft\ManganelTest\Models\TrashableModel;
use MongoId;
use UnitTester;

class TrashableDocumentTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillDeleteIndexOfTrashableDocumentAfterTrashingAndRestoreItOnRestore()
	{
		$model = new TrashableModel;
		$model->_id = new MongoId;

		$model->title = 'Connecticut is a state';

		// Mock signal receive save
		(new Receiver())->onSave(new AfterSave($model));

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
		];
		$found = $client->get($get)['_source'];
		$this->assertSame($model->title, $found['title']);

		// Should be handle by event handler from Receiver
		$model->trash();

		try
		{
			$client->get($get);
			$this->assertFalse(true, 'That missing exception was not thrown');
		}
		catch (Missing404Exception $e)
		{
			$this->assertTrue(true, 'That missing exception was thrown');
		}

		$trash = new Trash();
		$trashed = $trash->find();

		$this->assertNotNull($trashed, 'That item was found in trash');

		$restored = $trashed->restore();

		$this->assertTrue($restored, 'That item was successfully restored');

		$restoredIndex = $client->get($get)['_source'];
		$this->assertSame($model->title, $restoredIndex['title'], 'That restored item is back in index');
	}

}
