<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Elasticsearch\Client;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * Manganel
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Manganel
{

	const DefaultIndexId = 'manganel';

	public $hosts = [
		'localhost:9200'
	];
	public $auth = null;
	public $username = '';
	public $password = '';
	public $params = [];

	public $index = 'my_index';

	public $indexId = self::DefaultIndexId;

	/**
	 *
	 * @var Client
	 */
	private $_client = null;

	/**
	 *
	 * @var EmbeDi
	 */
	private $_di = null;

	public function __construct($indexId = self::DefaultIndexId)
	{
		if(!$indexId)
		{
			$indexId = self::DefaultIndexId;
		}
		$this->indexId = $indexId;
		$this->_di = new EmbeDi($this->indexId);
		$this->_di->configure($this);
	}

	public static function create($model)
	{
		$meta = ManganelMeta::create($model);
		return new static($meta->type()->indexId);
	}

	public function init()
	{
		$this->_di->store($this);
	}

	public function getClient()
	{
		if (null === $this->_client)
		{
			$this->params['hosts'] = $this->hosts;
			$this->params['connectionParams']['auth'] = [
				$this->username,
				$this->password,
				$this->auth
			];
			$this->_client = new Client($this->params);
		}
		return $this->_client;
	}

}
