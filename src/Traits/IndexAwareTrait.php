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
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, IndexAwareInterface::class)); // @codeCoverageIgnore
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
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, IndexAwareInterface::class)); // @codeCoverageIgnore
		}
		$this->index = $index;
		return $this;
	}

}
