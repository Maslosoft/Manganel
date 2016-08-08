<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Manganel;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
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

	/**
	 * TODO Enforce lowercase
	 */
	public $index = 'my_index';
	public $indexId = self::DefaultIndexId;

	/**
	 *
	 * @var Client
	 */
	private $client = null;

	/**
	 * Dependency injection container
	 * @var EmbeDi
	 */
	private $di = null;

	/**
	 * Instances of manganel
	 * @var Manganel[]
	 */
	private static $mnl = [];

	/**
	 * Hash map of class name to id. This is to reduce overhead of Mangan::fromModel()
	 * @var string[]
	 */
	private static $classToId = [];

	/**
	 * Class constructor
	 * @codeCoverageIgnore This is implicitly tested
	 * @param string $indexId
	 */
	public function __construct($indexId = self::DefaultIndexId)
	{
		if (empty($indexId))
		{
			$indexId = self::DefaultIndexId;
		}
		$this->indexId = $indexId;
		$this->di = new EmbeDi($this->indexId);
		$this->di->configure($this);

		if (empty(self::$mnl[$indexId]))
		{
			self::$mnl[$indexId] = $this;
		}
	}

	/**
	 * @codeCoverageIgnore This is implicitly tested
	 * @param AnnotatedInterface $model
	 * @return static
	 */
	public static function create(AnnotatedInterface $model)
	{
		$key = get_class($model);
		if (isset(self::$classToId[$key]))
		{
			$indexId = self::$classToId[$key];
		}
		else
		{
			$indexId = ManganelMeta::create($model)->type()->indexId;
			self::$classToId[$key] = $indexId;
		}
		return static::fly($indexId);
	}

	/**
	 * Get flyweight instance of Manganel component.
	 * Only one instance will be created for each `$indexId`.
	 *
	 * @codeCoverageIgnore This is implicitly tested
	 * @new
	 * @param string $indexId
	 * @return Manganel
	 */
	public static function fly($indexId = self::DefaultIndexId)
	{
		if (empty($indexId))
		{
			$indexId = self::DefaultIndexId;
		}
		if (empty(self::$mnl[$indexId]))
		{
			self::$mnl[$indexId] = new static($indexId);
		}
		return self::$mnl[$indexId];
	}

	/**
	 * @codeCoverageIgnore This is implicitly tested
	 */
	public function init()
	{
		$this->di->store($this);
	}

	/**
	 * @codeCoverageIgnore This is implicitly tested
	 * @return Client
	 */
	public function getClient()
	{
		if (null === $this->client)
		{
			$this->params['hosts'] = $this->hosts;
			$this->params['connectionParams']['auth'] = [
				$this->username,
				$this->password,
				$this->auth
			];
			$cb = ClientBuilder::create();
			$cb->setHosts($this->hosts);
			$this->client = $cb->build();
		}
		return $this->client;
	}

}
