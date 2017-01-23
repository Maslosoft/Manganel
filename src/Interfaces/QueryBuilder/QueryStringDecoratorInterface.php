<?php

namespace Maslosoft\Manganel\Interfaces\QueryBuilder;

use Maslosoft\Manganel\SearchCriteria;

/**
 * QueryStringDecoratorInterface
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria);
}
