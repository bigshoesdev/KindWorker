<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\Notification;
use app\models\search\Notification as NotificationSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\models\AppNotification;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends TController {
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
												// 'add',
												'view',
												'update',
												'delete',
												'ajax',
												'application',
												'mass' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												
												'view' 
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
	 * Send Notifications to users.
	 *
	 * @return mixed
	 */
	public function actionApplication() {
		$model = new AppNotification ();

		/*
		 * if (! ($model->isAllowed ()))
		 * throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
		 * if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
		 * Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 * return TActiveForm::validate ( $model );
		 * }
		 */

          if (! ($model->isAllowed ()))
		        throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
		  if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             return TActiveForm::validate ( $model );
          }

		if ($model->load ( Yii::$app->request->post () )) {
			if (! empty ( $_POST ['AppNotification'] ['customer_message'] ) && isset ( $_POST ['AppNotification'] ['customer_message'] )) {
				foreach ( $model->customer as $mod ) {
					$model = new AppNotification ();
					$model->user_id = $mod;
					$model->message = $_POST ['AppNotification'] ['customer_message'];
					$model->create_user_id = \yii::$app->user->id;
					$model->save ();
				}
				Yii::$app->getSession ()->setFlash ( 'success', ' Message has been sent Successfully.' );
				return $this->redirect ( [ 
						'/user/dashboard' 
				] );
			}
			if (! empty ( $_POST ['AppNotification'] ['worker_message'] ) && isset ( $_POST ['AppNotification'] ['worker_message'] )) {
				
				foreach ( $model->worker as $mod ) {
					$model = new AppNotification ();
					$model->user_id = $mod;
					$model->message = $_POST ['AppNotification'] ['worker_message'];
					$model->create_user_id = \yii::$app->user->id;

					$model->save ();
				}
				Yii::$app->getSession ()->setFlash ( 'success', ' Message has been sent Successfully.' );
				return $this->redirect ( [ 
						'/user/dashboard' 
				] );
			}
		} else {
			return $this->render ( 'add', [
					'model' => $model,
            ] );
		}
	}
	/**
	 * Lists all Notification models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new NotificationSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Notification model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel ( $id );
		$this->updateMenuItems ( $model );
		return $this->render ( 'view', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Creates a new Notification model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		$model = new Notification ();
		$model->loadDefaultValues ();
		// $model->state_id = Notification::STATE_ACTIVE;
		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( $post ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		}
		$this->updateMenuItems ();
		return $this->render ( 'add', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Updates an existing Notification model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel ( $id );
		
		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( $post ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		}
		$this->updateMenuItems ( $model );
		return $this->render ( 'update', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Deletes an existing Notification model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$model = Notification::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		;
		
		$model->delete ();
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the Notification model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return Notification the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id, $accessCheck = true) {
		if (($model = Notification::findOne ( $id )) !== null) {
			
			if ($accessCheck && ! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
			
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	protected function updateMenuItems($model = null) {
		switch (\Yii::$app->controller->action->id) {
			
			case 'add' :
				{
				}
				break;
			case 'index' :
				{
				}
				break;
			case 'update' :
				{
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-plus"></span>',
							'title' => Yii::t ( 'app', 'add' ),
							'url' => [ 
									'add' 
							] 
						// 'visible' => User::isAdmin ()
					);
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							] 
						// 'visible' => User::isAdmin ()
					);
				}
				break;
			default :
			case 'view' :
				{
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							] 
						// 'visible' => User::isAdmin ()
					);
					if ($model != null)
					{
					
						$this->menu ['delete'] = array (
								'label' => '<span class="glyphicon glyphicon-trash"></span>',
								'title' => Yii::t ( 'app', 'Delete' ),
								'url' => [
										'delete',
										'id' => $model->id
								],
							 'visible' => User::isAdmin ()
						);
					}
				}
		}

	}
}
