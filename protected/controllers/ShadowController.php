<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\search\Shadow as ShadowSearch;
use app\models\Shadow;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * ShadowController implements the CRUD actions for Shadow model.
 */
class ShadowController extends TController {
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
												'view',
												'update',
												'delete',
												'ajax',
												'login',
												'logout'
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
								],
								[
										
										'actions' => [
												'index',
												'add',
												'view',
												'update',
												'delete',
												'ajax'
										],
										'allow' => true,
										'matchCallback' => function () {
										return User::isAdmin ();
										}
										] 
						] 
				],
				'verbs' => [ 
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all Shadow models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ShadowSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Shadow model.
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
	 * Creates a new Shadow model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionAdd() {
		$model = new Shadow ();
		$model->loadDefaultValues ();
		// $model->state_id = Shadow::STATE_ACTIVE;
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
	public function actionLogin($id) {
		if ($id == Yii::$app->user->id) {
			// Shadow on Self not Allowed
			$this->refresh ();
		}
		$user = $this->findUserModel ( $id );
		
		if ($user) {
			$shadow = new Shadow ();
			$shadow->to_id = $user->id;
			$shadow->state_id = Shadow::STATE_ACTIVE;
			if ($shadow->save ()) {
				Yii::$app->user->switchIdentity($user );
				
				
			}
		}
		return $this->goHome ();
	}
	public function actionLogout($id) {
		$shadow = $this->findModel ( $id );
		if ($shadow) {
			$shadow->state_id = Shadow::STATE_INACTIVE;
			
			if ($shadow->save ()) {
				Yii::$app->user->switchIdentity( $shadow->createUser );
				$shadow->delete ();
			}
		}
		return $this->goHome ();
	}
	/**
	 * Updates an existing Shadow model.
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
	 * Deletes an existing Shadow model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$model = $this->findModel ( $id );
		
		$model->delete ();
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the Shadow model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return Shadow the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Shadow::findOne ( $id )) !== null) {
			
			if (! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
			
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	protected function findUserModel($id) {
		if (($model = User::findOne ( $id )) !== null) {
			
			if (! ($model->isAllowed ()))
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
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							],
							'visible' => User::isAdmin () 
					);
				}
				break;
			case 'index' :
				{
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-plus"></span>',
							'title' => Yii::t ( 'app', 'Add' ),
							'url' => [ 
									'add' 
							],
							'visible' => User::isAdmin () 
					);
				}
				break;
			case 'update' :
				{
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-plus"></span>',
							'title' => Yii::t ( 'app', 'add' ),
							'url' => [ 
									'add' 
							],
							'visible' => User::isAdmin () 
					);
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							],
							'visible' => User::isAdmin () 
					);
				}
				break;
			default :
			case 'view' :
				{
					
					if ($model != null)
						$this->menu ['update'] = array (
								'label' => '<span class="glyphicon glyphicon-pencil"></span>',
								'title' => Yii::t ( 'app', 'Update' ),
								'url' => [ 
										'update',
										'id' => $model->id 
								],
								'visible' => User::isAdmin () 
						);
				}
		}
	}
}
