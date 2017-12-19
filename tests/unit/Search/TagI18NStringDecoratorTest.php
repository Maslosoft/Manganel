<?php

namespace Search;

use Maslosoft\ManganelTest\Models\ModelWithBoostedFieldAndTagsI18NAsString;

require_once 'TagDecoratorTest.php';

class TagI18NStringDecoratorTest extends TagDecoratorTest
{

	protected function getModel()
	{
		return new ModelWithBoostedFieldAndTagsI18NAsString;
	}

	protected function getTags()
	{

		return implode(', ', parent::getTags());
	}

	protected function getTag()
	{
		return implode(', ', parent::getTag());
	}

}
