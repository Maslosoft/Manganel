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

namespace Maslosoft\Manganel\Signals;

use Maslosoft\Ilmatar\Components\Managers\Model\SearchIndex;
use Maslosoft\Signals\Interfaces\SignalInterface;
use Maslosoft\Signals\Interfaces\SlotInterface;

/**
 * IndexableSlot
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexableSlot implements SlotInterface
{

	private $document = null;

	public function result()
	{
		return $this->document;
	}

	public function setSignal(SignalInterface $indexable): void
	{
		$document = new SearchIndex;
		$document->model = $indexable;
		$this->document = $document;
	}

}
