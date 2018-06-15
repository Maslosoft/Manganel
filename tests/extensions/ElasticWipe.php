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
		$mnl = Manganel::fly();
		$params = ['index' => strtolower($mnl->index)];
		try
		{
			// Wait a bit to avoid illegal index states
			usleep(275000);
			$mnl->getClient()->indices()->delete($params);
		}
		catch (Missing404Exception $e)
		{
			// Skip missing indexes
		}
		try
		{
			// Wait a bit to avoid illegal index states
			usleep(275000);
			$mnl->getClient()->indices()->create($params);
		}
		catch (Missing404Exception $e)
		{
			// Skip missing indexes
		}
	}

}
