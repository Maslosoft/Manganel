<?php

namespace Search;

use Maslosoft\ManganelTest\Models\ModelWithBoostedFieldAndTags;

require_once 'TagDecoratorTest.php';

class TagStringDecoratorTest extends TagDecoratorTest
{

	protected function getModel()
	{
		return new ModelWithBoostedFieldAndTags;
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
