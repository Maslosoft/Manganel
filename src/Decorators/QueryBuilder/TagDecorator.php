<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Interfaces\QueryBuilder\ConditionDecoratorInterface;
use Maslosoft\Manganel\SearchCriteria;
use Maslosoft\Manganel\Traits\ManganelAwareTrait;

/**
 * TagDecorator
 *
 * NOTE: This decorator must be at beginning, as it modifies criteria!
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TagDecorator implements ConditionDecoratorInterface, ManganelAwareInterface
{

	use ManganelAwareTrait;

	/**
	 * Field which should be used as a tag filter
	 * @var string
	 */
	public $field = '';

	public function decorate(&$conditions, SearchCriteria $criteria)
	{
		assert(!empty($this->field), sprintf('Property `field` is required for `%s`', __CLASS__));
		$pattern = '~\[[\s\p{L}]+\]~';
		$query = $criteria->getSearch();
		$matches = [];
		if(!preg_match_all($pattern, $query, $matches))
		{
			return;
		}
		if(count($matches[0]) > 1)
		{
			echo '';
		}
		$field = $this->field;
		foreach($criteria->getModels() as $model)
		{
			// Skip field if not exists on model
			if(ManganMeta::create($model)->{$this->field} === false)
			{
				continue;
			}
			$cd = new ConditionDecorator($model);
			$data = $cd->decorate($this->field);
			$field = key($data);
		}
		foreach($matches[0] as $match)
		{
			$query = str_replace($match, '', $query);
			$conditions[] = [
				'term' => [
					$field => trim($match, '[]')
				]
			];
		}
		
		$criteria->search($query);
	}

	public function getKind()
	{
		return self::KindFilter;
	}

}
