<?php

use Maslosoft\Mangan\Decorators\EmbedRefArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefDecorator;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Manganel\Adapters\Finder\ElasticSearchCursor;
use Maslosoft\Manganel\Decorators\IndexDecorator;
use Maslosoft\Manganel\Decorators\MaxScoreDecorator;
use Maslosoft\Manganel\Decorators\ScoreDecorator;
use Maslosoft\Manganel\Decorators\SearchClassNameDecorator;
use Maslosoft\Manganel\Decorators\UnderscoreIdFieldDecorator;
use Maslosoft\Manganel\Filters\SearchFilter;
use Maslosoft\Manganel\Sanitizers\DateWriteEsSanitizer;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\Manganel\SearchCriteriaArray;

// @codeCoverageIgnoreStart
// Mangan additional configuration
return [
	'decorators' => [
		ElasticSearchCursor::class => [
		// See notes in ElasticSearchCursor::execute()
		],
		SearchArray::class => [
			SearchClassNameDecorator::class,
			UnderscoreIdFieldDecorator::class,
			ScoreDecorator::class,
			MaxScoreDecorator::class,
			IndexDecorator::class,
			EmbedRefDecorator::class,
			EmbedRefArrayDecorator::class,
			I18NDecorator::class,
		],
		SearchCriteriaArray::class => [
			I18NDecorator::class,
		]
	],
	'filters' => [
		SearchArray::class => [
			SearchFilter::class,
		],
	],
	'sanitizersMap' => [
		SearchArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteEsSanitizer::class
		],
		SearchCriteriaArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteEsSanitizer::class
		]
	]
];
