<?php

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Abstracts\AbstractFinder;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Profillers\NullProfiler;
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
			$model = current($model);
		}
		else
		{
			// Ensure array and that model is set for further usage
			$model = $models;
			$models = [$models];
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

		/**
		 * TODO Use profiler here if any available
		 */
		$this->setProfiler(new NullProfiler);
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
		return SearchArray::toModel($data);
	}

}
