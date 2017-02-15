<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Helpers;

use Maslosoft\Manganel\Decorators\QueryBuilder\MultiModelDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\SingleModelDecorator;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * QueryBuilderDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryBuilderDecorator implements ModelsAwareInterface
{

	use UniqueModelsAwareTrait;

	/**
	 * Manganel instance
	 * @var Manganel
	 */
	private $manganel = null;

	public function __construct(Manganel $manganel)
	{
		$this->manganel = $manganel;
	}

	public function decorate(&$body, SearchCriteria $criteria)
	{

		$models = $this->getModels();
		$numModels = count($models);
		assert($numModels > 0);
		if ($numModels === 1)
		{
			(new SingleModelDecorator())
					->setManganel($this->manganel)
					->decorate($body, $criteria);
		}
		else
		{
			(new MultiModelDecorator($models))
					->setManganel($this->manganel)
					->decorate($body, $criteria);
		}
	}

}
