<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Traits\Model\SimpleTreeTrait;

/**
 * ModelWithSimpleTree
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithSimpleTree extends Document
{

	use SimpleTreeTrait;
}
