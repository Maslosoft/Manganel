<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sanitizers;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Manganel\Meta\ManganelMeta;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\ManganelTest\Models\ModelWithSimpleTree;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

/**
 * MongoIdTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoIdTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillReadWriteProperType()
	{
		$sanitizer = new MongoWriteStringId;

		$id = new MongoId;
		$stringId = $sanitizer->write(null, $id);
		$objectId = $sanitizer->read(null, $id);

		$this->assertIsString($stringId);

		$this->assertInstanceOf(MongoId::class, $objectId);
	}

	public function testIfWillProperlyRemapSanitizerWithNullValue()
	{
		$model = new ModelWithSimpleTree();
		$model->parentId = null;


		$data = SearchArray::fromModel($model);

		$this->assertNull($data['parentId']);

		$fromArray = SearchArray::toModel($data);

		$this->assertNull($fromArray->parentId);
	}

	public function testIfWillProperlyRemapSanitizer()
	{
		$model = new ModelWithSimpleTree();
		$model->parentId = new MongoId;

		$meta = ManganelMeta::create($model)->field('parentId');

		$this->assertTrue(!empty($meta->sanitizer), 'That has sanitizer explicitly set');


		$data = SearchArray::fromModel($model);

		$this->assertIsString($data['parentId']);

		$fromArray = SearchArray::toModel($data);

		$this->assertIsString($fromArray->parentId);
	}

}
