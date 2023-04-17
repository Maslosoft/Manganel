<?php

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Manganel\Interfaces\ScoreAwareInterface;
use Maslosoft\Manganel\Traits\ScoreAwareTrait;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithBoostedField
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithBoostedField2 implements AnnotatedInterface, ScoreAwareInterface
{

	use ScoreAwareTrait;

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @SearchBoost(5)
	 * @var string
	 */
	public $title = '';

	/**
	 * @SearchBoost(0.2)
	 * @var string
	 */
	public $description = '';

}
