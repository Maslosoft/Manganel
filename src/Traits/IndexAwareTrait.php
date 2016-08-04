<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Manganel\Interfaces\IndexAwareInterface;
use UnexpectedValueException;

/**
 * IndexAwareTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait IndexAwareTrait
{

	private $index = '';

	/**
	 * Get currently used index
	 * @return string
	 */
	public function getIndex()
	{
		if (!$this instanceof IndexAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, IndexAwareInterface::class));
		}
		return $this->index;
	}

	/**
	 * Set currently used index
	 * @param string $index
	 * @return static
	 */
	public function setIndex($index)
	{
		if (!$this instanceof IndexAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, IndexAwareInterface::class));
		}
		$this->index = $index;
		return $this;
	}

}
