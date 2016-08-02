<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
