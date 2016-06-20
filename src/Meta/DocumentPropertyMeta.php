<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
