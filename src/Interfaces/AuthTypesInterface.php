<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
