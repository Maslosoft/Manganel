<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Zamm\ShortNamer;
use Maslosoft\Zamm\Widgets\DocNavRecursive;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
/* @var $ml Manganel */
ShortNamer::defaults()->md();
$ml = new ShortNamer(Manganel::class);

?>

<template>docs</template>
<title>Query Decorators</title>

# Query Decorators

<p class="alert alert-warning">
This is advanced topic, for basic usage it is not required. Misused will yield
unexpected results.
</p>

## Mangan cooperation

Manganel is prepared to work with [Mangan project][mangan], thus it is prepared
to translate MongoDB queries for [ElasticSearch][es]. Also based on model
definition or manually set conditions [Manganel][ml] will *[try][issues]* to
create proper query consumable by search engine.

This is done by Query Decorators, which translate internal query parameters to
[ElasticSearch][es] compatible ones. Decorators setup is done by setting
<?= $ml->decorators; ?> property. It should contains array of configurations
(at mininmum class names) of decorators. Some sane defaults are set, so that
[Manganel][ml] will do search - misconfiguring decorators might yield unexpected
results.

### Optional decorators

<?= new DocNavRecursive; ?>

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
[embedi]: /embedi/
[ml]: /manganel/
[es]: https://elastic.co/
[issues]: https://github.com/Maslosoft/Manganel/issues/new?title=[QueryDecorator]