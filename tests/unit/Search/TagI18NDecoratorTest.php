<?php

namespace Search;

use Maslosoft\ManganelTest\Models\ModelWithBoostedFieldAndTagsI18N;

require_once 'TagDecoratorTest.php';

class TagI18NDecoratorTest extends TagDecoratorTest
{

	protected function getModel()
	{
		return new ModelWithBoostedFieldAndTagsI18N;
	}

	protected function getTags()
	{

		return ['large', 'crowded', 'famous'];
	}

	protected function getTag()
	{
		return ['airport'];
	}

}
