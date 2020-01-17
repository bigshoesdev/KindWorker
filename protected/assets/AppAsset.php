<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\assets;
use yii\web\AssetBundle;

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
class AppAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [ ];
	public $jsOptions=['position'=> \yii\web\View::POS_HEAD];

	public $js = [ ];
	public $depends = [ 
			'yii\web\YiiAsset',
			'yii\bootstrap\BootstrapAsset' 
	];
}
