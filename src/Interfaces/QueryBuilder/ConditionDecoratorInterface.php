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
interface ConditionDecoratorInterface
{

	// NOTE: Do not rename this contants, as these are used directly by ES
	const KindMust = 'must';
	const KindFilter = 'filter';
	const KindMustNot = 'must_not';
	const KindShould = 'should';

	public function decorate(&$conditions, SearchCriteria $criteria);

	/**
	 * Get kind of query, should return one of interface constants:
	 *
	 * * KindMust
	 * * KindMustNot
	 * * KindFilter
	 * * KindShould
	 *
	 * Or false to skip condition.
	 *
	 * @return string|boolean
	 */
	public function getKind();
}
