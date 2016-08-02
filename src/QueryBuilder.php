<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
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
	private $manganel = null;

	/**
	 * Annotated model
	 * @var AnnotatedInterface
	 */
	private $model;

	public function __construct($model)
	{
		$this->model = $model;
		$this->manganel = Manganel::create($this->model);
	}

	public function range($field, $start, $end = null)
	{
		return $this;
	}

	public function search($q = null)
	{
		$params = [
			'index' => strtolower($this->manganel->index),
			'type' => CollectionNamer::nameCollection($this->model),
			'body' => [
				'query' => [
					'query_string' => [
						'query' => $q
					]
				]
			]
		];

		return $this->manganel->getClient()->search($params);
	}

	/**
	 * TODO Return true if search has hits
	 * @return boolean
	 * @throws Exception
	 */
	public function hasHits()
	{
		throw new Exception('Not implemented');
		return true;
	}

}
