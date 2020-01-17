<?php
require 'common.php';

require (VENDOR_PATH . 'autoload.php');
require (VENDOR_PATH . 'yiisoft/yii2/Yii.php');

$config = require (DB_CONFIG_PATH. '/console.php');

$application = new yii\console\Application ( $config );

ob_start ();
try {
	$application->runAction ( 'migrate/up', [ 
			'interactive' => false 
	] );
} catch ( \Exception $ex ) {
	echo $ex->getMessage ();
}
echo htmlentities ( ob_get_clean (), null, Yii::$app->charset );
