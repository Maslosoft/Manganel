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

namespace Maslosoft\Manganel\Interfaces;

/**
 * Implement this interface to provide score for:
 *
 * * Models via SearchProvider
 *
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ScoreAwareInterface
{

	public function getScore();

	public function setScore($score);
}
