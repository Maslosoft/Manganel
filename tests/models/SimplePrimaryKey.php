<?php

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
 * Simple primary model
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimplePrimaryKey implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Maslosoft\Mangan\Annotations\PrimaryKey
	 * @Sanitizer('MongoStringId')
	 */
	public $primaryKey = '';
	public $title = '';

}
