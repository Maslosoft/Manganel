<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel\Decorators\QueryBuilder;

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
		foreach($matches as $group)
		{
			$match = $group[0];
			$query = str_replace($match, '', $query);
			$criteria->addCond($this->field, '==', trim($match, '[]'));
		}
		
		$criteria->search($query);
	}

	public function getKind()
	{
		return false;
	}

}
