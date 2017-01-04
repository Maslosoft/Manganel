<?php

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
