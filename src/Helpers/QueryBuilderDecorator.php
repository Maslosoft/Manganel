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
