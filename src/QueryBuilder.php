<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Helpers\CollectionNamer;

/**
 * QueryBuilder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class QueryBuilder
{

	/**
	 * Manganel instance
	 * @var Manganel
	 */
	private $_manganel = null;

	/**
	 * Annotated model
	 * @var IAnnotated
	 */
	private $_model;

	public function __construct($model)
	{
		$this->_model = $model;
		$this->_manganel = Manganel::create($this->_model);
	}

	public function range($field, $start, $end = null)
	{
		return $this;
	}

	public function search($q = null)
	{
		$params = [
			'index' => strtolower($this->_manganel->index),
			'type' => CollectionNamer::nameCollection($this->_model),
			'body' => [
				'query' => [
					'query_string' => [
						'query' => $q
					]
				]
			]
		];
		
		return $this->_manganel->getClient()->search($params);
	}

}
