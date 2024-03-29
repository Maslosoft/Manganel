<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Mangan\Interfaces\ModelInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * WithBaseAttributes
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithBaseAttributesSecond implements ModelInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;
	public $int = 23;
	public $string = 'test';
	public $bool = true;
	public $float = 0.23;
	public $array = [];
	public $null = null;

}
