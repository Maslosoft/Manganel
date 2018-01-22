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

namespace Maslosoft\Manganel\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ModelsAwareInterface
{

	public function getModels();

	public function setModels($models);

	public function addModel(AnnotatedInterface $model);

	public function removeModel(AnnotatedInterface $model);

	public function hasModel(AnnotatedInterface $model);
}
