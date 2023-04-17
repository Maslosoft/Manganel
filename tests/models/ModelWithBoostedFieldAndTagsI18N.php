<?php

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;
use Maslosoft\Mangan\Traits\I18NAbleTrait;
use Maslosoft\Manganel\Interfaces\ScoreAwareInterface;
use Maslosoft\Manganel\Traits\ScoreAwareTrait;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithBoostedFieldAndTagsI18N
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithBoostedFieldAndTagsI18N implements AnnotatedInterface,
		InternationalInterface,
		ScoreAwareInterface
{

	use ScoreAwareTrait,
	  I18NAbleTrait;

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

	/**
	 * @SanitizerArray(StringSanitizer)
	 * @I18N
	 * @see StringSanitizer
	 * @var string
	 */
	public $tag = [];

}
