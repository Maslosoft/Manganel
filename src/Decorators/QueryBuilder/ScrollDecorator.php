<?php

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Manganel\Interfaces\QueryBuilder\BodyDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;

/**
 * ScrollDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScrollDecorator implements BodyDecoratorInterface
{

	public function decorate(&$body, SearchCriteria $criteria)
	{
		if ($criteria->getLimit() || $criteria->getOffset())
		{
			if (is_int($criteria->getOffset()))
			{
				$body['from'] = $criteria->getOffset();
			}
			if (is_int($criteria->getLimit()))
			{
				$body['size'] = $criteria->getLimit();
			}
		}
	}

}
