<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
 * SimpleModel
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimpleModelThird implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 *
	 * @var type
	 */
	public $title = '';

}
