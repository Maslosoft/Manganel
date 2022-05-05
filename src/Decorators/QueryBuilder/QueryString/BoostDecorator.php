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

namespace Maslosoft\Manganel\Decorators\QueryBuilder\QueryString;

use Maslosoft\Manganel\Decorators\QueryBuilder\Common\FieldsBoosting;
use Maslosoft\Manganel\Interfaces\QueryBuilder\QueryStringDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use function array_values;

/**
 * BoostDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BoostDecorator extends FieldsBoosting implements QueryStringDecoratorInterface
{

	public function decorate(&$queryStringParams, SearchCriteria $criteria): void
	{
		$fields = $this->getBoostFactors($criteria);

		// No custom boost, ignore
		if (empty($fields))
		{
			return;
		}
		$boosts = [];
		foreach ($fields as $name => $boost)
		{
			$boosts[] = sprintf('%s^%f', $name, $boost);
		}

		// Add also _all or it would search only boosted fields
		$boosts[] = '_all';

		sort($boosts);

		// NOTE: It is possibly important to have regular array here
		$queryStringParams['fields'] = array_values($boosts);
	}


}
