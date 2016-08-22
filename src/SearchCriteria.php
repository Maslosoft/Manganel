<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use Maslosoft\Mangan\Criteria;

/**
 * SearchCriteria
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchCriteria extends Criteria
{

	private $query = '';

	public function search($query)
	{
		$this->query = $query;
	}

	public function getSearch()
	{
		return $this->query;
	}

}
