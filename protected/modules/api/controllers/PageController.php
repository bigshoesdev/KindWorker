<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\Page;
use app\modules\api\controllers\ApiTxController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * PageController implements the API actions for Page model.
 */
class PageController extends ApiTxController {
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
												'delete',
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
												'update',
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
	 * Lists all Page models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\Page" );
	}
	
	/**
	 * Displays a single app\models\Page model.
	 * 
	 * @return mixed
	 */
	public function actionGet($id) {
		$data = [ ];
		$page = Page::find ()->where ( [
				'type_id' => $id,
		] )->one ();
		if (! empty ( $page )) {
			$data ['status'] = self::API_OK;
			$data ['detail'] = $page->asJson ();
		} else {
			$data ['error'] = 'Nothing found.';
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Creates a new Page model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionAdd() {
		return $this->txSave ( "app\models\Page" );
	}
	
	/**
	 * Updates an existing Page model.
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
	 * Deletes an existing Page model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @return mixed
	 */
	public function actionDelete($id) {
		return $this->txDelete ( $id, "app\models\Page" );
	}

	
}
