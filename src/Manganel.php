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

use Closure;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use Maslosoft\Mangan\Profillers\NullProfiler;
use Maslosoft\Manganel\Decorators\QueryBuilder\ConditionDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\ConditionsDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\MoreLikeThisDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\Operators\InDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\Operators\NotDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\Operators\OrDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\Operators\RangeDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\Operators\SimpleTermDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\QueryString\BoostDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\QueryString\PrefixQueryDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\ScrollDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\SearchDecorator;
use Maslosoft\Manganel\Decorators\QueryBuilder\TagDecorator;
use Maslosoft\Manganel\Interfaces\ManganelAwareInterface;
use Maslosoft\Manganel\Meta\ManganelMeta;

/**
 * Manganel
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Manganel
{

	const DefaultIndexId = 'manganel';

	public $decorators = [
		SearchCriteria::class => [
			ConditionDecorator::class,
			ConditionsDecorator::class,
			ScrollDecorator::class,
			SearchDecorator::class,
			InDecorator::class,
			OrDecorator::class,
			NotDecorator::class,
			RangeDecorator::class,
			SimpleTermDecorator::class,
			BoostDecorator::class,
			PrefixQueryDecorator::class,
			MoreLikeThisDecorator::class
		]
	];
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
	 * Whether to use refresh option when indexing document.
	 * NOTE: Due to performance reasons, this should be set to `true` only when
	 * really necessary - so it can be also callback.
	 *
	 * Callback function signature:
	 * ```
	 * function(AnnotatedInterface $model)
	 * ```
	 * @var string|Closure
	 */
	public $refresh = false;

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
	 * Version number holder
	 * @var string
	 */
	private static $_version = null;

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
	 * Profiler instance
	 * @var ProfilerInterface
	 */
	private $profiler = null;

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
		$decorators = $this->decorators;

		$this->indexId = $indexId;
		$this->di = new EmbeDi($this->indexId);
		$this->di->configure($this);

		$this->decorators = array_merge_recursive($this->decorators, $decorators);

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
	 * Get mangan version
	 * @return string
	 */
	public function getVersion()
	{
		if (null === self::$_version)
		{
			self::$_version = require __DIR__ . '/version.php';
		}
		return self::$_version;
	}

	/**
	 * Drop current index
	 * @return bool
	 */
	public function drop()
	{
		$params = [
			'index' => strtolower($this->index)
		];
		$result = $this->getClient()->indices()->delete($params);
		if (is_array($result) && array_key_exists('acknowledged', $result) && $result['acknowledged'])
		{
			return true;
		}
		return false;
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

	/**
	 * Get profiler instance. This is guaranted, if not configured will return NullProfiller.
	 * @see NullProfiler
	 * @return ProfilerInterface
	 */
	public function getProfiler()
	{
		if (null === $this->profiler)
		{
			$this->profiler = new NullProfiler;
		}
		if ($this->profiler instanceof ManganelAwareInterface)
		{
			$this->profiler->setManganel($this);
		}
		return $this->profiler;
	}

	/**
	 * Set profiler instance
	 * @param ProfilerInterface $profiller
	 * @return static
	 */
	public function setProfiler(ProfilerInterface $profiller)
	{
		$this->profiler = $profiller;
		return $this;
	}

}
