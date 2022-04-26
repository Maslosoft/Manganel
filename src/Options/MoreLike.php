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

namespace Maslosoft\Manganel\Options;

use Maslosoft\Manganel\IndexManager;
use function is_array;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Manganel\Helpers\Strings;
use Maslosoft\Manganel\Manganel;
use ReflectionClass;

/**
 * Holder for more like options
 * @package Maslosoft\Manganel\Options
 */
class MoreLike
{
	/**
	 * Models to search from similar
	 * @var AnnotatedInterface[]
	 */
	public array $models = [];

	/**
	 * A list of fields to fetch and analyze the text from. Defaults to the _all field for free text and to all
	 * possible fields for document inputs.
	 * @var array
	 */
	public array $fields = [];

	/**
	 *
	 * The unlike parameter is used in conjunction with like in order not to select terms found in a chosen set of
	 * documents. In other words, we could ask for documents like: "Apple", but unlike: "cake crumble tree". The syntax
	 * is the same as like.
	 * @var array
	 */
	public array $unlike = [];

	/**
	 * Extra text to search for
	 * @var string[]
	 */
	public array $texts = [];

	/**
	 * The maximum number of query terms that will be selected. Increasing this value gives greater accuracy at the
	 * expense of query execution speed.
	 * @var int
	 */
	public int $maxQueryTerms = 25;

	/**
	 * The minimum term frequency below which the terms will be ignored from the input document.
	 * @var int
	 */
	public int $minTermFreq = 2;

	/**
	 * The minimum document frequency below which the terms will be ignored from the input document.
	 * @var int
	 */
	public int $minDocFreq = 5;

	/**
	 * The maximum document frequency above which the terms will be ignored from the input document. This could be
	 * useful in order to ignore highly frequent words such as stop words
	 * @var int|null
	 */
	public ?int $maxDocFreq = null;

	/**
	 * The minimum word length below which the terms will be ignored. The old name min_word_len is deprecated.
	 * @var int
	 */
	public int $minWordLength = 0;

	/**
	 * The maximum word length above which the terms will be ignored. The old name max_word_len is deprecated.
	 * @var int|null
	 */
	public ?int $maxWordLength = null;

	/**
	 * An array of stop words. Any word in this set is considered "uninteresting" and ignored. If the analyzer allows
	 * for stop words, you might want to tell MLT to explicitly ignore them, as for the purposes of document similarity
	 * it seems reasonable to assume that "a stop word is never interesting".
	 * @var array
	 */
	public array $stopWords = [];

	/**
	 * MoreLikeOptions constructor.
	 * @param AnnotatedInterface|AnnotatedInterface[]|DataProviderInterface|null $models
	 */
	public function __construct($models = null)
	{
		if ($models instanceof DataProviderInterface)
		{
			$this->models = $models->getData();
		}
		elseif ($models instanceof AnnotatedInterface)
		{
			$this->models = [$models];
		}
		elseif (is_array($models))
		{
			$this->models = $models;
		}
	}

	public function toArray(): array
	{
		$options = [];
		$like = [];
		if (!empty($this->texts))
		{
			foreach ((array)$this->texts as $text)
			{
				$like[] = $text;
			}
		}
		if (!empty($this->models))
		{
			foreach ($this->models as $model)
			{
				$like[] = [
					'_index' => Manganel::create($model)->index,
					'_type' => IndexManager::DocType,
					'_id' => (string)$model->_id
				];
			}
		}

		if (!empty($like))
		{
			$options['like'] = $like;
		}

		if (!empty($this->fields))
		{
			// TODO Must decorate, ie `title` to be `title.en` etc.
			$options['fields'] = $this->fields;
		}

		if(!empty($this->unlike))
		{
			// TODO Must consider also models array etc.
			$options['unlike'] = $this->unlike;
		}

		$keys = [
			'maxQueryTerms',
			'minTermFreq',
			'minDocFreq',
			'maxDocFreq',
			'minWordLength',
			'maxWordLength',
			'stopWords',
		];
		$info = new ReflectionClass($this);
		$defaults = $info->getDefaultProperties();

		foreach ($keys as $name)
		{
			$value = $this->$name;
			if (null === $value)
			{
				continue;
			}
			if (is_array($value) && empty($value))
			{
				continue;
			}
			if ($defaults[$name] === $value)
			{
				continue;
			}
			$key = Strings::decamelize($name, '_');
			$options[$key] = $value;
		}
		return $options;
	}
}