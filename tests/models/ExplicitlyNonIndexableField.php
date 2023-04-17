<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * This model is meant to **be** indexed but without password and details fields.
 *
 * @SearchIndex
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ExplicitlyNonIndexableField implements AnnotatedInterface
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

	/**
	 * @Secret
	 * @var string
	 */
	public $password = '';

	/**
	 *
	 * @Search(false)
	 * @var string
	 */
	public $details = '';

	/**
	 * @Persistent(false)
	 * @var string
	 */
	public $garbage = '';

}
