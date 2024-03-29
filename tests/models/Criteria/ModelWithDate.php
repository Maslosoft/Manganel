<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 23.01.18
 * Time: 14:07
 */

namespace Maslosoft\ManganelTest\Models\Criteria;


use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use MongoDB\BSON\UTCDateTime as MongoDate;

/**
 * Class ModelWithDate
 *
 * @SearchIndex
 *
 * @package Maslosoft\ManganelTest\Models\Criteria
 */
class ModelWithDate extends Document
{
	public $title = '';

	public $number = 0;

	/**
	 * @Sanitizer(DateSanitizer)
	 *
	 * @see DateSanitizer
	 * @var MongoDate
	 */
	public $date;
}