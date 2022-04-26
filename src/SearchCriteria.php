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

use Maslosoft\Manganel\Meta\ManganelMeta;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeAwareInterface;
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

	private string $query = '';

	private array $boosted = [];

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
	public function add($model): SearchCriteria
	{
		$this->addModel($model);
		return $this;
	}

	/**
	 * Perform scored full text search
	 * @param $query
	 */
	public function search($query): void
	{
		$this->query = $query;
	}

	/**
	 * Boost fields in form of field name as key and float as value. This will
	 * override any other boosts.
	 *
	 * Example `$fields` parameter:
	 * ```
	 * [
	 * 	'keywords' => 7.2
	 * ]
	 * ```
	 *
	 *
	 * @param $fields
	 * @return void
	 */
	public function boost($fields): void
	{
		$this->boosted = $fields;
	}

	public function getBoosted(): array
	{
		$boosted = [];
		$model = $this->getModel();
		// when no model provided could not determine boosting
		if ($model !== null)
		{
			$meta = ManganelMeta::create($model);
			$boosted = $meta->properties('searchBoost');
		}

		// Override manually set in Criteria
		foreach($this->boosted as $fieldName => $boost)
		{
			$boosted[$fieldName] = $boost;
		}

		foreach($boosted as $fieldName => $boost)
		{
			if($boost === 1.0)
			{
				unset($boosted[$fieldName]);
			}
		}

		return $boosted;
	}

	/**
	 * Fond document similar to provided document(s) or other options
	 * @param MoreLike $options
	 */
	public function moreLike(MoreLike $options): void
	{
		$this->moreLike = $options;
		// Example query
//		{
//			"query": {
//			"more_like_this": {
//				"like": [
//        {
//			"_index": "dev_maslosoft_com",
//          "_type": "_doc",
//          "_id": "5ad49885a3d24b6a4d288be3"
//        }
//      ],
//      "min_term_freq": 1,
//      "max_query_terms": 25
//    }
//  }
//}
	}

	/**
	 * @return MoreLike|null
	 */
	public function getMoreLike(): ?MoreLike
	{
		return $this->moreLike;
	}

	public function getSearch()
	{
		return $this->query;
	}

}
