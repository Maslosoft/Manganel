<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder\Traits;

/**
 * ConditionsAware
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ConditionsAware
{

	private $conditions = [];

	public function getConditions()
	{
		return $this->conditions;
	}

	public function setConditions($conditions)
	{
		$this->conditions = $conditions;
		return $this;
	}

}
