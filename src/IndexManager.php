<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Elasticsearch\Client;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Manganel\Exceptions\ManganelException;
use Maslosoft\Manganel\Meta\ManganelMeta;

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
	private $manganel = null;

	/**
	 * Model meta data
	 * @var ManganelMeta
	 */
	private $meta = null;

	/**
	 * Annotated model
	 * @var AnnotatedInterface
	 */
	private $model;

	/**
	 * Whether model is indexable
	 * @var bool
	 */
	private $isIndexable = false;

	public function __construct($model)
	{
		$this->model = $model;
		if (!isset($this->model->_id))
		{
			throw new ManganelException(sprintf('Propoerty `_id` is not set in model `%s`, this is required by Manganel', get_class($this->model)));
		}
		$this->manganel = Manganel::create($this->model);
		$this->meta = ManganelMeta::create($this->model);
		if (!empty($this->meta->type()->indexId) && false !== $this->meta->type()->indexId)
		{
			$this->isIndexable = true;
		}
	}

	public function index()
	{
		if (!$this->isIndexable)
		{
			return;
		}
		// NOTE: Transformer must ensure that _id is string, not MongoId
		$params = [
			'body' => SearchArray::fromModel($this->model)
		];
		$this->getClient()->index($this->getParams($params));
	}

	public function delete()
	{
		if (!$this->isIndexable)
		{
			return;
		}
		$this->getClient()->delete($this->getParams());
	}

	public function get($id = null)
	{
		if (!$this->isIndexable)
		{
			return;
		}
		$params = $id ? ['id' => (string) $id] : [];
		$data = $this->getClient()->get($this->getParams($params))['_source'];
		return SearchArray::toModel($data);
	}

	/**
	 * Get client
	 * @return Client
	 */
	public function getClient()
	{
		return $this->manganel->getClient();
	}

	private function getParams($params = [])
	{
		$result = [
			'index' => strtolower($this->manganel->index),
			'type' => CollectionNamer::nameCollection($this->model),
			'id' => (string) $this->model->_id
		];
		return array_merge($result, $params);
	}

}
