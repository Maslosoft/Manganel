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

namespace Maslosoft\Manganel\Interfaces\QueryBuilder;

use Maslosoft\Manganel\SearchCriteria;

/**
 * QueryBuilderDecoratorInterface
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface BodyDecoratorInterface
{

	public function decorate(&$body, SearchCriteria $criteria);
}
