<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
