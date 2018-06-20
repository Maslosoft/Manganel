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

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Manganel\Interfaces\MaxScoreAwareInterface;
use UnexpectedValueException;

/**
 * MaxScoreAwareTrait
 *
 * @see MaxScoreAwareInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait MaxScoreAwareTrait
{

	private $maxScore = 0.0;

	public function getMaxScore()
	{
		if (!$this instanceof MaxScoreAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, MaxScoreAwareInterface::class)); // @codeCoverageIgnore
		}
		return $this->maxScore;
	}

	public function setMaxScore($score)
	{
		if (!$this instanceof MaxScoreAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, MaxScoreAwareInterface::class)); // @codeCoverageIgnore
		}
		$this->maxScore = floatval($score);
		return $this;
	}

}
