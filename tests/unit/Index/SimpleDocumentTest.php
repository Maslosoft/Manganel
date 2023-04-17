<?php

namespace Index;

use Codeception\TestCase\Test;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Manganel\Helpers\TypeNamer;
use Maslosoft\Manganel\IndexManager;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\Receiver;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\ManganelTest\Models\ExplicitlyNonIndexableModel;
use Maslosoft\ManganelTest\Models\NonIndexableModel;
use Maslosoft\ManganelTest\Models\SimpleModel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SimpleDocumentTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfCanStoreSimpleDocument(): void
	{
		$model = new SimpleModel();
		$model->_id = new MongoId;
		$model->title = 'Connecticut is a state';

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$params = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
			'body' => SearchArray::fromModel($model)
		];

		$client->index($params);

		$get = $params;
		unset($get['body']);
		$found = $client->get($get)['_source'];
		$this->assertSame($model->title, $found['title']);
	}

	public function testIfWillStoreSimpleDocumentFromMockSignal()
	{
		$model = new SimpleModel();
		$model->_id = new MongoId;

		$model->title = 'Connecticut is a state';

		// Mock signal receive
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
	}

	public function testIfWillStoreSimpleDocumentFromRealSignal()
	{
		$model = new SimpleModel();
		$model->_id = new MongoId;

		$model->title = 'Connecticut is a state';

		EntityManager::create($model)->save();

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
		];
		$found = $client->get($get)['_source'];
		$this->assertSame($model->title, $found['title']);
	}

	public function testIfWillDeleteSimpleDocumentFromMockSignal()
	{
		$model = new SimpleModel();
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

		// Mock signal receive delete
		(new Receiver())->onDelete(new AfterDelete($model));

		try
		{
			$client->get($get);
			$this->fail('That missing exception was not thrown');
		}
		catch (Missing404Exception $e)
		{
			$this->assertTrue(true, 'That missing exception was thrown');
		}
	}

	public function testIfWillDeleteSimpleDocumentFromRealSignal()
	{
		$model = new SimpleModel();
		$model->_id = new MongoId;

		$model->title = 'Connecticut is a state';

		// Signal save index
		EntityManager::create($model)->save();

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
		];
		$found = $client->get($get)['_source'];
		$this->assertSame($model->title, $found['title']);

		// Signal delete index
		EntityManager::create($model)->delete();

		try
		{
			$client->get($get);
			$this->assertFalse(true, 'That missing exception was not thrown');
		}
		catch (Missing404Exception $e)
		{
			$this->assertTrue(true, 'That missing exception was thrown');
		}
	}

	public function testIfWillIgnoreNonIndexableFromMockSignal()
	{
		$model = new NonIndexableModel();
		$model->_id = new MongoId;

		$model->title = 'Alabama is a state';

		// Mock signal receive
		(new Receiver())->onSave(new AfterSave($model));

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
		];
		try
		{
			$found = $client->get($get)['_source'];
			$this->assertFalse(true, 'That missing exception was not thrown');
		}
		catch (Missing404Exception $e)
		{
			$this->assertTrue(true, 'That missing exception was thrown');
		}
	}

	public function testIfWillIgnoreExplicitlyNonIndexableFromMockSignal()
	{
		$model = new ExplicitlyNonIndexableModel();
		$model->_id = new MongoId;

		$model->title = 'Alabama is a state';

		// Mock signal receive
		(new Receiver())->onSave(new AfterSave($model));

		$mnl = Manganel::fly();
		$client = $mnl->getClient();

		$get = [
			'index' => $mnl->index,
			'type' => IndexManager::DocType,
			'id' => (string) $model->_id,
		];
		try
		{
			$found = $client->get($get)['_source'];
			$this->assertFalse(true, 'That missing exception was not thrown');
		}
		catch (Missing404Exception $e)
		{
			$this->assertTrue(true, 'That missing exception was thrown');
		}
	}

}
