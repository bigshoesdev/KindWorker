<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
$params = require (__DIR__ . '/params.php');

$config = [ 
 	'id' => 'fix square',

 
		'basePath' => dirname ( __DIR__ ),
		'bootstrap' => [ 
				'log', 
		],
		'vendorPath' => VENDOR_PATH,
		'controllerNamespace' => 'app\commands',

		'components' => [

		        'cache' => [
						'class' => 'yii\caching\FileCache' 
				],
				'log' => [ 
						'targets' => [ 
								[ 
										'class' => 'yii\log\FileTarget',
										'levels' => [ 
												'error',
												'warning' 
										] 
								] 
						] 
				],

				'mailer' => require (MAILER_CONFIG_FILE_PATH),
			
		],
		'params' => $params 
];
if (file_exists ( DB_CONFIG_FILE_PATH )) {

	$config ['components'] ['db'] = require (DB_CONFIG_FILE_PATH);
}
$config ['modules'] ['backup'] = [ 
		'class' => 'app\modules\backup\Module' 
];

$config ['modules'] ['install'] = [
		'class' => 'app\modules\install\Install',
		'sqlfile' => DB_BACKUP_FILE_PATH . '/install.sql'
];
return $config;
