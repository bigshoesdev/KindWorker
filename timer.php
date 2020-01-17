<?php
require 'common.php';

$config = require (DB_CONFIG_PATH . '/console.php');

require (VENDOR_PATH . 'autoload.php');
require (VENDOR_PATH . 'yiisoft/yii2/Yii.php');

$application = new yii\console\Application ( $config );

ob_start ();
try {
	$application->runAction ( 'timer/email', [ 
			'interactive' => false 
	] );
} catch ( \Exception $ex ) {
	echo $ex->getMessage ();
}
try
{
	$application->runAction ( 'timer/app-noty', [
			'interactive' => false
	] );
}
catch ( \Exception $ex )
{
	echo $ex->getMessage ();
}
try
{
	$application->runAction ( 'timer/app-noty-del', [
			'interactive' => false
	] );
}
catch ( \Exception $ex )
{
	echo $ex->getMessage ();
}
echo htmlentities ( ob_get_clean (), null, Yii::$app->charset );
