<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\Job;
use app\models\search\Job as JobSearch;
use app\models\search\JobImage as JobImageSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\models\JobImage;
use app\models\Notification;
use app\models\AuthSession;

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends TController {
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
												//'add',
												'view',
												'update',
												'delete',
												'ajax',
												'booked-workers',
												'custom-job',
												'cancel' ,
												'mass',
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
				],
			/* 	'verbs' => [ 
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				]  */
		];
	}
	/**
	 * Cancel Job model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionCancel($id) {
		$model = $this->findModel ( $id );
		
		$model->state_id = Job::STATE_CANCEL;
		if ($model->save ()) {
			
			Yii::$app->getSession ()->setFlash ( 'success', 'Job Has been canceled Successfully & Notification has been sent to both users.' );
			
			$customer = AuthSession::find ()->where ( [ 
					'created_by_id' => $model->created_by_id 
			] )->one ();
			if (! empty ( $customer )) {
				$message = 'Admin has canceled this Job';
				Notification::notification ( new Job (), $message, $id, $customer->created_by_id );
			}
			if (! empty ( $model->worker_id )) {
				$worker = AuthSession::find ()->where ( [ 
						'created_by_id' => $model->worker_id 
				] )->one ();
				if (! empty ( $worker )) {
					$message = 'Admin has canceled this Job';
					Notification::notification ( new Job (), $message, $id, $worker->created_by_id );
				}
			}
			
			$searchModel = new JobImageSearch ();
			$dataProvider = $searchModel->search ( Yii::$app->request->queryParams, $id );
			$this->updateMenuItems ( $model );
			return $this->render ( 'view', [ 
					'model' => $model,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider 
			] );
		}
	}
	/**
	 * Lists all Job models.
	 *
	 * @return mixed
	 */
	public function actionBookedWorkers() {
		$searchModel = new JobSearch ();
		$dataProvider = $searchModel->searchBookedWorker ( Yii::$app->request->queryParams );
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Lists all Job models.
	 *
	 * @return mixed
	 */
	public function actionCustomJob() {
		$searchModel = new JobSearch ();
		$dataProvider = $searchModel->searchCustomJob ( Yii::$app->request->queryParams );
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Lists all Job models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new JobSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Job model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel ( $id );
		$searchModel = new JobImageSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams, $id );
		$this->updateMenuItems ( $model );
		return $this->render ( 'view', [ 
				'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Creates a new Job model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		$model = new Job ();
		$model->loadDefaultValues ();
		// $model->state_id = Job::STATE_ACTIVE;
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
	 * Updates an existing Job model.
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
	 * Deletes an existing Job model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$model = $this->findModel ( $id );
        if(file_exists(UPLOAD_PATH . $model->first_file))
		    unlink(UPLOAD_PATH . $model->first_file);
        if(file_exists(UPLOAD_PATH . $model->second_file))
            unlink(UPLOAD_PATH . $model->second_file);
		if($model->job_type == Job::JOB_TYPE_BOOOKED){
			$model->delete ();
			return $this->redirect ( [
					'/job/booked-workers'
			] );
		}elseif($model->job_type == Job::JOB_TYPE_CUSTOM){
			$model->delete ();
			return $this->redirect ( [
					'/job/custom-job'
			] );
		}else{
			$model->delete ();
			return $this->redirect ( [
					'index'
			] );
		}
	
	}
	
	/**
	 * Finds the Job model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return Job the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id, $accessCheck = true) {
		if (($model = Job::findOne ( $id )) !== null) {
			
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
			
				}
				break;
			default :
			case 'view' :
				{
					if ($model != null && $model->state_id != Job::STATE_CANCEL) {
						$this->menu ['cancel'] = array (
								'label' => '<span class="glyphicon glyphicon-pencil">Cancel-Job</span>',
								'title' => Yii::t ( 'app', 'Cancel' ),
								'url' => [ 
										'cancel',
										'id' => $model->id 
								],
							 'visible' => User::isAdmin () || User::isSubAdmin()
						);
					}

					if ($model != null ) {
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
