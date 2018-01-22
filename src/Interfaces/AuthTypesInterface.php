<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/manganel
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/manganel/
 */

namespace Maslosoft\Manganel\Interfaces;

/**
 * Auth types
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface AuthTypesInterface
{

	const Basic = 'Basic';
	const Digests = 'Digests';
	const NTLM = 'NTLM';
	const Any = 'Any';

}
