<?php

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel;

use function is_array;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeAwareInterface;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeInterface;
use Maslosoft\Mangan\Transformers\CriteriaArray;
use Maslosoft\Manganel\Options\MoreLike;
use Maslosoft\Manganel\Traits\UniqueModelsAwareTrait;

/**
 * SearchCriteria
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SearchCriteria extends Criteria implements ConditionDecoratorTypeAwareInterface
{

	use UniqueModelsAwareTrait;

	private $query = '';

	/**
	 * @var MoreLike|null
	 */
	private $moreLike = null;

	public function __construct($criteria = null, AnnotatedInterface $model = null)
	{
		parent::__construct($criteria, $model);
		if (!empty($model))
		{
			$this->add($model);
		}
	}

	/**
	 * @internal This is used by condition decorator
	 * @return string
	 */
	public function getDecoratorType()
	{
		return SearchCriteriaArray::class;
	}

	/**
	 * Set type for searching
	 * @param AnnotatedInterface $model
	 * @return Criteria|void
	 */
	public function setModel(AnnotatedInterface $model)
	{
		$this->add($model);
		parent::setModel($model);
	}

	/**
	 * Add model to search more types
	 * @param $model
	 * @return $this
	 */
	public function add($model)
	{
		$this->addModel($model);
		return $this;
	}

	/**
	 * Perform scored full text search
	 * @param $query
	 */
	public function search($query)
	{
		$this->query = $query;
	}

	/**
	 * Fond document similar to provided document(s) or other options
	 * @param MoreLike $options
	 */
	public function moreLike(MoreLike $options)
	{
		$this->moreLike = $options;
		// Example query
//		{
//			"query": {
//			"more_like_this": {
//				"like": [
//        {
//			"_index": "dev_maslosoft_com",
//          "_type": "Content_Page",
//          "_id": "5ad49885a3d24b6a4d288be3"
//        }
//      ],
//      "min_term_freq": 1,
//      "max_query_terms": 25
//    }
//  }
//}
		$this->moreLike = $options;
	}

	/**
	 * @return MoreLike|null
	 */
	public function getMoreLike()
	{
		return $this->moreLike;
	}

	public function getSearch()
	{
		return $this->query;
	}

}
