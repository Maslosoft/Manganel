<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Manganel\Interfaces\MaxScoreAwareInterface;
use Maslosoft\Manganel\Interfaces\ScoreAwareInterface;
use Maslosoft\Manganel\Traits\MaxScoreAwareTrait;
use Maslosoft\Manganel\Traits\ScoreAwareTrait;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * SimpleModel
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithScore implements AnnotatedInterface, MaxScoreAwareInterface, ScoreAwareInterface
{

	use MaxScoreAwareTrait,
	  ScoreAwareTrait;

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
