<?php

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Abstracts\AbstractFinder;
use Maslosoft\Mangan\Interfaces\FinderInterface;

/**
 * SearchFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchFinder extends AbstractFinder implements FinderInterface
{

	/**
	 * Constructor
	 *
	 * @param object $model Model instance
	 * @param IndexManager $im
	 * @param Manganel $manganel
	 */
	public function __construct($model, $im = null, $manganel = null)
	{
		$this->model = $model;
		$this->sm = new ScopeManager($model);
		if (null === $manganel)
		{
			$manganel = Mangan::fromModel($model);
		}
		$this->adapter = new MongoAdapter($model, $manganel, $im);
		$this->mn = $manganel;
	}

	/**
	 * Create model related finder.
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

}
