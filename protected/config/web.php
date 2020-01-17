<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
$params = require (__DIR__ . '/params.php');

$config = [ 
		'id' => 'basic',
		'name' => 'Kind Worker',
		'basePath' => dirname ( __DIR__ ),
		'defaultRoute' => 'site/index',
		'bootstrap' => [ 
				'log' 
			// 'setting'
		],
		'vendorPath' => VENDOR_PATH,
		'timeZone' => date_default_timezone_get (),
		'components' => [ 
				
				'request' => [ 
						'enableCsrfValidation' => YII_ENV == 'dev' ? false : true,
						'cookieValidationKey' => md5 ( 'base' ),
						'parsers' => [ 
								'application/json' => 'yii\web\JsonParser' 
						] 
				],
				
				'settings' => [ 
						'class' => 'app\components\Settings' 
				
				],
				
				'cache' => [ 
						'class' => 'yii\caching\FileCache' 
				],
				'user' => [ 
						'class' => 'app\components\WebUser' 
				
				],

                'braintree' => [
                    'class' => 'bryglen\braintree\Braintree',
                    'environment' => 'sandbox',
                    'merchantId' => '9qm2xmd2j7649fr6',
                    'publicKey' => 'rj45mc72srvj8gk3',
                    'privateKey' => 'cdbf5b38dcde6d10334207263ab03a74',
                ],

                'apns' => [
                    'class' => 'bryglen\apnsgcm\Apns',
                    'environment' => 'sandbox',
                    'pemFile' => dirname(__FILE__).'/apns-dev.pem',
                    // 'retryTimes' => 3,
                    'options' => [
                        'sendRetryTimes' => 5
                    ]
                ],
                'gcm' => [
                    'class' => 'bryglen\apnsgcm\Gcm',
                    'apiKey' => 'your_api_key',
                ],
                // using both gcm and apns, make sure you have 'gcm' and 'apns' in your component
                'apnsGcm' => [
                    'class' => 'bryglen\apnsgcm\ApnsGcm',
                    // custom name for the component, by default we will use 'gcm' and 'apns'
                    //'gcm' => 'gcm',
                    //'apns' => 'apns',
                ],

				'mailer' => require (MAILER_CONFIG_FILE_PATH),
				'log' => [ 
						'traceLevel' => YII_DEBUG ? 3 : 0,
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
				
				'formatter' => [ 
						'class' => 'yii\i18n\Formatter',
						'thousandSeparator' => ',',
						'decimalSeparator' => '.',
						'defaultTimeZone' => date_default_timezone_get (),
						'datetimeFormat' => 'php:Y-m-d h:i:s A',
						'dateFormat' => 'php:Y-m-d' 
				],
				'urlManager' => [ 
						'class' => 'app\components\TUrlManager',
						
						'rules' => [ 
								
								[ 
										'pattern' => 'sitemap',
										'route' => 'sitemap/default/index',
										'suffix' => '.xml' 
								],
								
								[ 
										'pattern' => 'aboutus',
										'route' => 'site/about' 
								],
								[ 
										'pattern' => 'contactus',
										'route' => 'site/contact' 
								],
								'<controller:\w+>/<id:\d+>/<title>' => '<controller>/view',
								
								'<action:about|careers|privacy|settings|guidelines|copyright|contact|notice|faq|terms|login>' => 'site/<action>',
								'<controller:\w+>/<id:\d+>' => '<controller>/view',
								'<controller:\w+>/<id:\d+>/<title>' => '<controller>/view',
								'<controller:blog>/<action:\w+>/<id:\d+>/<title>' => '/blog/post/<action>',
								'<controller:\w+>/<action:\w+>/<id:\d+>/<title>' => '<controller>/<action>',
								'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
								'<controller:\w+>/<action:\w+>' => '<controller>/<action>' 
						] 
				],
				
				'view' => [ 
						'theme' => [ 
								'class' => 'app\components\AppTheme',
								'name' => 'green' 
						] 
				] 
		],
		'params' => $params,
		
		'modules' => [ 
				
				'api' => [ 
						'class' => 'app\modules\api\Api' 
				],
				'sitemap' => [ 
						'class' => 'app\modules\sitemap\Sitemap',
						'models' => [ 
								// your models
								// 'app\models\Post',
								'app\models\User' 
						],
						'urls' => [ 
								// your additional urls
								[ 
										'loc' => '/blog/index' 
								],
								
								[ 
										'loc' => '/user/index' 
								],
								
								[ 
										'loc' => '/site/about' 
								],
								
								[ 
										'loc' => '/site/contact' 
								] 
						],
						'enableGzip' => true 
				] 
		] 
];

if (file_exists ( DB_CONFIG_FILE_PATH )) {
	
	$config ['components'] ['db'] = require (DB_CONFIG_FILE_PATH);
} else {
	$config ['modules'] ['install'] = [ 
			'class' => 'app\modules\install\Install',
			'sqlfile' => DB_BACKUP_FILE_PATH . '/install.sql' 
	];
	define ( 'MODE_INSTALL', true );
}
if (YII_ENV == 'dev') {
	// configuration adjustments for 'dev' environment
	$config ['bootstrap'] [] = 'debug';
	$config ['modules'] ['debug'] = 'yii\debug\Module';
	
	$config ['modules'] ['tugii'] = [
			'class' => 'app\modules\tugii\Module'
	];
	$config ['components'] ['errorHandler'] = [ 
			'errorAction' => 'log/custom-error' 
	];
} else {
	$config ['components'] ['errorHandler'] = [ 
			'errorAction' => 'log/custom-error' 
	];
}


$config ['modules'] ['backup'] = [ 
		'class' => 'app\modules\backup\Module' 
];
$config ['modules'] ['blog'] = [ 
		'class' => 'app\modules\blog\Module' 
];
$config ['modules'] ['membership'] = [ 
		'class' => 'app\modules\membership\Module' 
];
$config ['modules'] ['seo'] = [ 
		'class' => 'app\modules\seo\manager\Module' 
];
$config ['modules'] ['invitation'] = [ 
		'class' => 'app\modules\invitation\Module' 
];

$config ['modules'] ['invitation_content'] = [ 
		'class' => 'app\modules\invitation_content\Module' 
];

/*
 * if (defined ( 'MAINTANANCE' )) {
 * $config ['catchAll'] = [
 * 'site/notice'
 * ];
 * }
 */
return $config;