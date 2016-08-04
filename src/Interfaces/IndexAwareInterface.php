<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Interfaces;

/**
 * Implement this interface to provide index for:
 *
 * * Models via SearchProvider
 * 
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexAwareInterface
{

	/**
	 * Get currently used index
	 * @return string
	 */
	public function getIndex();

	/**
	 * Set currently used index
	 * @param string $index
	 * @return static
	 */
	public function setIndex($index);
}