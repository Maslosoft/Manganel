<?php

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Abstracts\AbstractFinder;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Traits\Finder\FinderHelpers;
use Maslosoft\Manganel\Adapters\Finder\ElasticSearchAdapter;
use Maslosoft\Manganel\Interfaces\ModelsAwareInterface;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * SearchFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchFinder extends AbstractFinder implements FinderInterface, ModelsAwareInterface
{

	use FinderHelpers,
	  UniqueModelsAwareTrait;

	/**
	 * Constructor
	 *
	 * @param object|object[] $models Model or array of model instances
	 * @param IndexManager $im
	 * @param Manganel $manganel
	 */
	public function __construct($models, $im = null, $manganel = null)
	{
		if (is_array($models))
		{
			$model = current($models);
		}
		else
		{
			// Ensure array and that model is set for further usage
			$model = $models;
			$models = [$models];
		}
		foreach ($models as $modelIndex => $modelSignature)
		{
			if (is_string($modelSignature))
			{
				$models[$modelIndex] = new $modelSignature;
			}
		}
		if (is_string($model))
		{
			$model = new $model;
		}
		if (null === $manganel)
		{
			$manganel = Manganel::create($model);
		}
		assert($model instanceof AnnotatedInterface);

		$this->setModel($model);
		$this->setModels($models);
		$this->setScopeManager(new MultiScopeManager($model, $models));
		$this->setAdapter(new ElasticSearchAdapter($models));

		$this->setProfiler($manganel->getProfiler());
		$this->setFinderEvents(new Helpers\MultiFinderEvents());
		$this->withCursor(false);
	}

	/**
	 * Create search finder instance.
	 *
	 * @param AnnotatedInterface $model
	 * @param IndexManager $im
	 * @param Manganel $manganel
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model, $im = null, Manganel $manganel = null)
	{
		return new static($model, $im, $manganel);
	}

	protected function createModel($data)
	{
		// Do not use second param for multi model
		// compatibility
		assert(array_key_exists('_class', $data), 'Stored document must have `_class` field');
		return SearchArray::toModel($data);
	}

}
