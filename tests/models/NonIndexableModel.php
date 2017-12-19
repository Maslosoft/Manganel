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
 * This model is meant to be **not** indexed. Should be skipped by indexing signal.
 * Do not place any Manganel annotations here.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NonIndexableModel implements AnnotatedInterface
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
