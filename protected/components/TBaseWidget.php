<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

namespace app\components;

use Yii;

class TBaseWidget extends \yii\base\Widget {
	public $route;
	public $params;
	public function run() {
		if ($this->route === null && Yii::$app->controller !== null) {
			$this->route = Yii::$app->controller->getRoute ();
		}
		if ($this->params === null) {
			$this->params = Yii::$app->request->getQueryParams ();
		}
		$this->renderHtml ();
	}
	public function renderHtml() {
	}
}