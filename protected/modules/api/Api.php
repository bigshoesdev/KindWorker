<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

namespace app\modules\api;

use Yii;
use yii\web\Response;
use app\models\AuthSession;

/**
 * Api module definition class
 */
class Api extends \yii\base\Module {
	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'app\modules\api\controllers';
	
	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init ();
		
		// custom initialization code goes here
		Yii::$app->response->format = Response::FORMAT_JSON;
	}
	public function beforeAction($action) {
		if (parent::beforeAction ( $action )) {
			
			AuthSession::authenticateSession ();
			
			return true;
		} else
			return false;
	}
}
