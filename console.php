<?php
require 'common.php';

$config = require (DB_CONFIG_PATH. '/console.php');

require (VENDOR_PATH . 'autoload.php');
require (VENDOR_PATH . 'yiisoft/yii2/Yii.php');

$application = new yii\console\Application ( $config );


try {
	$application->run();
} catch ( \Exception $ex ) {
	echo $ex->getMessage ();
}
