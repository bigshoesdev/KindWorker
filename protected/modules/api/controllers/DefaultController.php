<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\components\TController;
use yii\filters\AccessControl;
use Yii\web\Response;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends TController {
	/**
	 * Renders the index view for the module
	 *
	 * @return string
	 */
	public function behaviors() {
		return [
				'access' => [
						'class' => AccessControl::className (),
						'only' => [
								'index'
						],
						'rules' => [
								[
										'actions' => [
												'index'
										],
										'allow' => true,
										'roles' => [
												'?',
												'*',
												'@'
										]
								]
						]
				]
		];
	}
	public function actionIndex() {
		
		\Yii::$app->response->format = Response::FORMAT_HTML;
		
		$filelist = $this->getTestFiles ();
		$test_file_array = array ();
		foreach ( $filelist as $file ) {
			$test_file_array [] = require ($file);
		}
		$dataProvider = new \yii\data\ArrayDataProvider ( [ 
				'allModels' => $test_file_array 
		] );
		
		return $this->render ( 'index', [ 
				'dataProvider' => $dataProvider 
		] );
	}
	public function getTestFiles() {
		$this->layout = '/../layouts/guest-main';
		$path = __DIR__ . '/../test/*';
		$filelist = glob ( $path . "*" );
		return $filelist;
	}
}
