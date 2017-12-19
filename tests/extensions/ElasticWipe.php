<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Extensions;

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Maslosoft\Manganel\Manganel;

/**
 * ElasticWipe
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ElasticWipe extends Extension
{

	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		// Wait a bit to avoid illegal index states
		usleep(275000);
		$mnl = Manganel::fly();
		$params = ['index' => strtolower($mnl->index)];
		try
		{
			$mnl->getClient()->indices()->delete($params);
		}
		catch (Missing404Exception $e)
		{
			// Skip missing indexes
		}
		try
		{
			$mnl->getClient()->indices()->create($params);
		}
		catch (Missing404Exception $e)
		{
			// Skip missing indexes
		}
		// Wait a bit to avoid illagal index states
		usleep(275000);
	}

}
