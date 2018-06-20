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

namespace Maslosoft\Manganel\Meta;

use Maslosoft\Mangan\Meta\DocumentTypeMeta as ManganTypeMeta;
use Maslosoft\Manganel\Manganel;

/**
 * Model meta container
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentTypeMeta extends ManganTypeMeta
{

	/**
	 * Index ID
	 * @see Manganel
	 * @var string
	 */
	public $indexId;

	/**
	 * Document type name. Used to search from child classes, 
	 * while indexed types are parent classes.
	 * @var string
	 */
	public $type = '';

}
