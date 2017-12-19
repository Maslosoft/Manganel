<?php

namespace Maslosoft\ManganelTest\Models\Criteria\With;

use Maslosoft\Mangan\Document;

/**
 * SimpleDocumentWithActive
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimpleDocumentWithActive extends Document
{

	public $active = false;

	/**
	 * @SearchBoost(2)
	 * @var string
	 */
	public $title = '';

}
