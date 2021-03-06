<?php

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;

/**
 * Composite primary model
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CompositePrimaryKey implements AnnotatedInterface
{

	/**
	 * Mock field, as it is required anyway...
	 *
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Maslosoft\Mangan\Annotations\PrimaryKey
	 * @Maslosoft\Mangan\Annotations\Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 */
	public $primaryOne = null;

	/**
	 * @Maslosoft\Mangan\Annotations\PrimaryKey
	 */
	public $primaryTwo = 0;

	/**
	 * @Maslosoft\Mangan\Annotations\PrimaryKey
	 */
	public $primaryThree = '';
	public $title = '';

}
