<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Interfaces\QueryBuilder;

/**
 * ConditionsAwareInterface
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ConditionsAwareInterface
{

	public function getConditions();

	public function setConditions($conditions);
}
