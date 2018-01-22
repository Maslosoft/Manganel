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

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * SearchCriteria
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchCriteria extends Criteria
{

	use UniqueModelsAwareTrait;

	private $query = '';

	public function __construct($criteria = null, AnnotatedInterface $model = null)
	{
		parent::__construct($criteria, $model);
		if (!empty($model))
		{
			$this->add($model);
		}
	}

	public function add($model)
	{
		$this->addModel($model);
		return $this;
	}

	public function search($query)
	{
		$this->query = $query;
	}

	public function getSearch()
	{
		return $this->query;
	}

}
