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

namespace Maslosoft\Manganel\Signals;

use Maslosoft\Signals\Interfaces\SlotInterface;
use Maslosoft\Signals\ISignal;

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

	public function setSignal(ISignal $indexable)
	{
		$this->document = $indexable;
	}

}
