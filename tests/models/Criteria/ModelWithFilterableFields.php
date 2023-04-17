<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\UTCDateTime as MongoDate;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithFilterableFields
 *
 * @SearchIndex
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithFilterableFields implements AnnotatedInterface
{

	const StatusActive = 1;
	const StatusInactive = 2;
	const StatusBanned = 3;
	const CodeInfo = 'info';
	const CodeNotice = 'notice';
	const CodeImportant = 'important';
	const CodeCritical = 'critical';

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * Status
	 * @var int
	 */
	public $status = 0;

	/**
	 * Status
	 * @var string
	 */
	public $code = '';

	/**
	 * @Sanitizer(DateSanitizer)
	 *
	 * @see DateSanitizer
	 *
	 * @var MongoDate
	 */
	public $date = null;
	public $title = '';

}
