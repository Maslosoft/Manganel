<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

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
