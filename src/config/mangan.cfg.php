<?php

use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\DateWriteUnixSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Manganel\Filters\SearchFilter;
use Maslosoft\Manganel\SearchArray;

// Mangan additional configuration
return [
	'filters' => [
		SearchArray::class => [
			SearchFilter::class,
		],
	],
	'sanitizersMap' => [
		SearchArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteUnixSanitizer::class
		],
	]
];
