<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Elasticsearch\Client;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Mangan\Transformers\FromRawArray;
use Maslosoft\Manganel\Exceptions\ManganelException;

/**
 * IndexMangager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexManager
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
		if (!$this->_model->_id)
		{
			throw new ManganelException(sprintf('Id is not set in model `%s`', get_class($this->_model)));
		}
		$this->_manganel = Manganel::create($this->_model);
	}

	public function index()
	{
		$params = [
			'body' => FromDocument::toRawArray($this->_model)
		];
		$this->getClient()->index($this->_getParams($params));
	}

	public function delete()
	{
		$this->getClient()->delete($this->_getParams());
	}

	public function get($id = null)
	{
		$params = $id ? ['id' => $id] : [];
		$data = $this->getClient()->get($this->_getParams($params))['_source'];
		return FromRawArray::toDocument($data);
	}

	/**
	 * Get client
	 * @return Client
	 */
	public function getClient()
	{
		return $this->_manganel->getClient();
	}

	private function _getParams($params = [])
	{
		$result = [
			'index' => strtolower($this->_manganel->index),
			'type' => CollectionNamer::nameCollection($this->_model),
			'id' => (string) $this->_model->_id
		];
		return array_merge($result, $params);
	}

}
