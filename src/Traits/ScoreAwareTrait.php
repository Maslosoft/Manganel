<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Traits;

use Maslosoft\Manganel\Interfaces\ScoreAwareInterface;
use UnexpectedValueException;

/**
 * ScoreAwareTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ScoreAwareTrait
{

	private $score = 0.0;

	public function getScore()
	{
		if (!$this instanceof ScoreAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, ScoreAwareInterface::class)); // @codeCoverageIgnore
		}
		return $this->score;
	}

	public function setScore($score)
	{
		if (!$this instanceof ScoreAwareInterface)
		{
			throw new UnexpectedValueException(sprintf('Class `%s` using `%s` must implement `%s`', get_class($this), __CLASS__, ScoreAwareInterface::class)); // @codeCoverageIgnore
		}
		$this->score = $score;
		return $this;
	}

}
