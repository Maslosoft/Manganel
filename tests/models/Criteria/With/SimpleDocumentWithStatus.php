<?php

namespace Maslosoft\ManganelTest\Models\Criteria\With;

use Maslosoft\Mangan\Document;

/**
 * SimpleDocumentWithStatus
 * @SearchIndex
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimpleDocumentWithStatus extends Document
{

	const StatusNew = 1;
	const StatusUsed = 2;
	const StatusDestroyed = 3;

	public $status = self::StatusNew;
	public $name = '';

}
