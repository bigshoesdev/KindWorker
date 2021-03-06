<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Notification;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;

/**
 * NotificationController implements the API actions for Notification model.
 */
class NotificationController extends ApiTxController {
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
												'add',
												'get',
												'update',
												'delete' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												'index',
												'get',
												'update' 
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'*' 
										] 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all Notification models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\search\Notification" );
	}
	
	/**
	 * Displays a single app\models\Notification model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\Notification" );
	}
	
	/**
	 * Creates a new Notification model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		return $this->txSave ( "app\models\Notification" );
	}
	
	/**
	 * Updates an existing Notification model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$data = [ ];
		$model = $this->findModel ( $id );
		if ($model->load ( Yii::$app->request->post () )) {
			
			if ($model->save ()) {
				
				$data ['status'] = self::API_OK;
				
				$data ['detail'] = $model;
			} else {
				$data ['error'] = $model->flattenErrors;
			}
		} else {
			$data ['error_post'] = 'No Data Posted';
		}
		
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Deletes an existing Notification model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionDelete($id) {
		return $this->txDelete ( $id, "app\models\Notification" );
	}
}
