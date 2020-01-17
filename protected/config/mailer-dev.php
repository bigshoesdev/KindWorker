<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
return [
		'class' => 'yii\swiftmailer\Mailer',
//		'transport' => [
//				'class' => 'Swift_SmtpTransport',
//				'host' => 'paris2.jiwebhosting.com',
//				'username' => 'test@web2.toxsl.in',
//				'password' => '*ToXSL975@',
//				'port' => '25'
//		],
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.zoho.com',
            'username' => 'support@kindworker.com',
            'password' => 'Testing12345!',
            'port' => '587',
            'encryption' => 'tls',
        ],
		'useFileTransport' => true
];
