<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link http://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Meta;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta as ManganPropertyMeta;

/**
 * DocumentPropertyMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentPropertyMeta extends ManganPropertyMeta
{

	public $searchDecorators = [];

	/**
	 * Whether property is searchable
	 * @var bool
	 */
	public $searchable = true;

}
