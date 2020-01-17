<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

/*
 * {"name":"Internal Server Error",
 * "message":"An internal server error occurred.",
 * "code":0,
 * "status":500}
 *
 */
abstract class ApiTxController extends Controller {
	const API_OK = 200;
	const API_NOK = 1000;
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'ruleConfig' => [ 
								'class' => AccessRule::className () 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
												'get',
												'update',
												'delete',
												'add' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												'index',
												'get' 
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'@' 
										] 
								] 
						] 
				],
				
				'verbs' => [ 
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post',
										'delete' 
								] 
						] 
				] 
		];
	}
	public $enableCsrfValidation = false;
	private $response = array (
			'status' => self::API_NOK 
	);
	public function beforeAction($action) {
		$this->response ['url'] = \yii::$app->request->pathInfo;
		return parent::beforeAction ( $action );
	}
	public function setResponse($data) {
		$this->response = ArrayHelper::merge ( $this->response, $data );
	}
	public function sendResponse($data = null) {
		if ($data != null)
			$this->setResponse ( $data );
		
		return $this->response;
	}
	public function txDelete($id, $modelClass) {
		$model = $this->findModel ( $id );
		
		if ($model->delete ()) {
			$data ['status'] = self::API_OK;
			$data ['msg'] = $modelClass . ' is deleted Successfully.';
			$this->setResponse ( $data );
		}
		
		return $this->sendResponse ();
	}
	public function txSave($modelClass) {
		$model = new $modelClass ();
		if ($model->load ( Yii::$app->request->post () )) {
			
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model;
				$this->setResponse ( $data );
			} else {
				$err = '';
				foreach ( $model->getErrors () as $error ) {
					$err .= implode ( ',', $error );
				}
				$data ['error'] = $err;
				$this->setResponse ( $data );
			}
		}
		return $this->sendResponse ();
	}
	public function txGet($id, $modelClass) {
		$model = $this->findModel ( $id );
		$data ['detail'] = $model;
		$data ['status'] = self::API_OK;
		$this->setResponse ( $data );
		return $this->sendResponse ();
	}
	public function txIndex($modelClass) {
		$model = new $modelClass ();
		$dataProvider = $model->search ( \Yii::$app->request->queryParams );
		$data ['list'] = array_map ( function ($data) {
			return $data->asJson ();
		}, $dataProvider->getModels () );
		$data ['count'] = $dataProvider->getTotalCount ();
		$data ['page'] = $dataProvider->getPagination ()->page + 1;
		$data ['status'] = self::API_OK;
		$this->setResponse ( $data );
		return $this->sendResponse ();
	}
	protected function findModel($id) {
		$modelClass = Inflector::id2camel ( \Yii::$app->controller->id );
		$modelClass = 'app\models\\' . $modelClass;
		if (($model = $modelClass::findOne ( $id )) !== null) {
			
			if (! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
			
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
