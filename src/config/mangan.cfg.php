<?php

// Mangan additional configuration
return [
	'sanitizersMap' => [
		SearchArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteUnixSanitizer::class
		],
	]
];
