<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
