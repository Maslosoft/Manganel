<?php

use Maslosoft\Manganel\Decorators\QueryBuilder\TagDecorator;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\SearchCriteria;

return [
	'manganel' => [
		'class' => Manganel::class,
		'decorators' => [
			SearchCriteria::class => [
				[
					'class' => TagDecorator::class,
					'field' => 'tag'
				]
			]
		]
	]
];
