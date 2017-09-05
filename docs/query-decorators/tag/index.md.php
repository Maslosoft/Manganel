<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Manganel\Decorators\QueryBuilder\TagDecorator;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
/* @var $ml Manganel */
ShortNamer::defaults()->md();
$ml = new ShortNamer(Manganel::class);
$t = new ShortNamer(TagDecorator::class);
?>

<template>docs</template>
<title>Tag</title>

# Tag

<p class="alert alert-warning">
This is advanced topic, for basic usage it is not required. Misused will yield
unexpected results.
</p>

## Tag Query Decorator

This decorator will allow tag-like syntax for query string to filter results along
with normal query, or no query at all to just return all documents with specific tag.

To search like that use square brackets. For example to search *famous* tagged
cities starting with word *New*, following query could be used:

> New [famous]

### Configuring
<?php ShortNamer::defaults()->html();?>
<p class="alert alert-warning">
Configuration for <?= $t; ?> should be placed at beginning of <?= $ml->decorators; ?> property.
</p>
<?php ShortNamer::defaults()->md();?>

At minimum it is required to provide field which contains tags. This field should
store comma or space separated values.

#### Example part of configuration containing <?= $t; ?>:


```
'decorators' = [
	SearchCriteria::class => [
		...
		[
			'class' => TagDecorator::class,
			'field' => 'tag'
		],
		...
];
```


[mangan]: /mangan/
[embedi]: /embedi/
[ml]: /manganel/
[es]: https://elastic.co/
[issues]: https://github.com/Maslosoft/Manganel/issues/new?title=[QueryDecorator]