<?php

use Maslosoft\EmbeDi\Adapters\ArrayAdapter;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\DateWriteUnixSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Mangan\Transformers\YamlArray;
use Maslosoft\Manganel\Manganel;
use Maslosoft\Manganel\SearchArray;
use Maslosoft\Signals\Signal;

date_default_timezone_set('Europe/Paris');

define('VENDOR_DIR', __DIR__ . '/../vendor');
define('YII_DIR', VENDOR_DIR . '/yiisoft/yii/framework/');
define('MANGAN_TEST_ENV', true);

if (!defined('JSON_PRESERVE_ZERO_FRACTION'))
{
	define('JSON_PRESERVE_ZERO_FRACTION', 1024);
}

require VENDOR_DIR . '/autoload.php';

// Invoker stub for windows
if (defined('PHP_WINDOWS_VERSION_MAJOR'))
{
	require __DIR__ . '/misc/Invoker.php';
}

$config = require __DIR__ . '/config.php';

EmbeDi::fly()->addAdapter(new ArrayAdapter($config));

$signals = new Signal();

$mangan = Mangan::fly();
$mangan->connectionString = 'mongodb://localhost:27017';
$mangan->dbName = 'ManganelTest';
$mangan->init();

$mangan2 = Mangan::fly('second');
$mangan2->connectionString = 'mongodb://localhost:27017';
$mangan2->dbName = 'ManganelTestSecond';
$mangan2->init();

$manganel = Manganel::fly();

$manganel->index = strtolower($mangan->dbName);

// Ensure that index is updated instantly
$manganel->refresh = true;
$manganel->hosts = [
	'localhost:9200'
];



$info = $manganel->getClient()->info();
echo "Manganel:" . $manganel->getVersion() . PHP_EOL;
echo "ES version: " . $info['version']['number'] . PHP_EOL;
echo "Using database: " . $mangan->dbName . PHP_EOL;
echo "Using database: " . $mangan2->dbName . PHP_EOL;
echo "Using index: " . $manganel->index . PHP_EOL;

define('ES_VERSION', $info['version']['number']);