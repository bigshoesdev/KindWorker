<?php
//date_default_timezone_set ( 'Asia/Kolkata' );
date_default_timezone_set ( 'UTC' );
defined ( 'YII_ENV' ) or define ( 'YII_ENV', 'dev' );

if (YII_ENV == 'dev') {
	error_reporting ( E_ALL );
	ini_set ( "display_errors", 1 );
	
	// remove the following lines when in production mode
	defined ( 'YII_DEBUG' ) or define ( 'YII_DEBUG', true );
}

defined ( 'DATECHECK' ) or define ( 'DATECHECK', "2017-03-20" );
// defined('MAINTANANCE') or define('MAINTANANCE',true);

// db config path setting
defined ( 'UPLOAD_PATH' ) or define ( 'UPLOAD_PATH', dirname ( __FILE__ ) . '/uploads/' );

// create directories if required
if (! file_exists ( UPLOAD_PATH ))
	@mkdir ( UPLOAD_PATH, 0775, true );
if (! file_exists ( dirname ( __FILE__ ) . '/assets' ))
	@mkdir ( dirname ( __FILE__ ) . '/assets', 0775, true );

defined ( 'DB_CONFIG_PATH' ) or define ( 'DB_CONFIG_PATH', dirname ( __FILE__ ) . '/protected/config/' );

defined ( 'DB_CONFIG_FILE_PATH' ) or define ( 'DB_CONFIG_FILE_PATH', DB_CONFIG_PATH . 'db-' . YII_ENV . '.php' );
defined ( 'MAILER_CONFIG_FILE_PATH' ) or define ( 'MAILER_CONFIG_FILE_PATH', DB_CONFIG_PATH . 'mailer-' . YII_ENV . '.php' );
defined ( 'DB_BACKUP_FILE_PATH' ) or define ( 'DB_BACKUP_FILE_PATH', dirname ( __FILE__ ) . '/protected/db' );

defined ( 'VENDOR_PATH' ) or define ( 'VENDOR_PATH', __DIR__ . '/vendor/' );
