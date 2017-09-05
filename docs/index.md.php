<?php

use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Ilmatar\Components\Controller;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
?>

<template>docs</template>
<title>Documentation</title>

# Documentation

Manganel is a full text search provider for [Mangan project][mangan].

### Installation

Use composer to install manganel:

```
composer require maslosoft/manganel
```

### Configuration

It is recommended to use [EmbeDi](/embedi/) to [configure Manganel](configuration/). This allow
separation configuration from actual usage. Minimum configuration required
is index name, using property `index`. Default configuration ID is `manganel`

*Use statements omitted*
```
$config = [
	'manganel' => [
		'class' => Manganel::class,
		// Index name
		'index' => 'quick-start',
	]
];
EmbeDi::fly()->addAdapter(new ArrayAdapter($config));
```
<!--
Now, assuming that we have some models ready, Mangan is ready to do it's duty.
When using built-in base documents classes, [active document][ad] (derived from active record)
pattern can be used, for instance:

```
$plant = new Plant;
$plant->name = 'Grass';
$plant->save();
```

Check [this repository for working example of Mangan](https://github.com/MaslosoftGuides/mangan.quick-start)
-->
[mangan]: /mangan/