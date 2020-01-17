<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\search\Category as CategorySearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends TController {
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
												'ajax' ,
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
								],
								[
										
										'actions' => [
												'index',
												'add',
												'view',
												'update',
												'delete',
												'ajax',
												'mass',
										],
										'allow' => true,
										'matchCallback' => function () {
										return User::isAdmin ();
										}
										] 
						] 
				],
				/* 'verbs' => [ 
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
	 * Lists all Category models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new CategorySearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider
		] );
	}
	
	/**
	 * Displays a single Category model.
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
	 * Creates a new Category model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		$model = new Category ();
		$model->loadDefaultValues ();
		$model->state_id = Category::STATE_ACTIVE;
		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( $post )) {
			if (isset ( $_FILES )) {
				$img = $model->saveUploadedFile ( $model, 'image_file' );
				if( isset($img['error']) && !empty($img['error']) ) {
					$model->addError ( "image_file", \yii::t ( 'app', $img) );
					return $this->render ( 'add', [
							'model' => $model
					] );
				}
			}
			if ($model->save ()) {
                return $this->render ( 'view', [
                    'model' => $model
                ] );

			}
		}
		
		$this->updateMenuItems ();
		return $this->render ( 'add', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel ( $id );
		$old_image = $model->image_file;
		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( $post )) {
			
			if (! empty ( $_FILES )) {
				
				if (! $model->saveUploadedFile ( $model, 'image_file' )) {
					$model->image_file = $old_image;
				}
			}
			
			if ($model->save ()) {
				
				return $this->redirect ( [ 
						'view',
						'id' => $model->id 
				] );
			}
		}
		$this->updateMenuItems ( $model );
		return $this->render ( 'update', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Deletes an existing Category model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$model = $this->findModel ( $id );
        if(file_exists(UPLOAD_PATH . $model->image_file))
            unlink(UPLOAD_PATH . $model->image_file);
		$model->delete ();
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the Category model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return Category the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id, $accessCheck = true) {
		if (($model = Category::findOne ( $id )) !== null) {
			
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
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							] 
					 
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
							] 
					 
					);
				}
				break;
			case 'update' :
				{
//					$this->menu ['add'] = array (
//							'label' => '<span class="glyphicon glyphicon-plus"></span>',
//							'title' => Yii::t ( 'app', 'add' ),
//							'url' => [
//									'add'
//							]
//
//					);
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							] 
				 
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
					 
					);
					if ($model != null) {
						$this->menu ['update'] = array (
								'label' => '<span class="glyphicon glyphicon-pencil"></span>',
								'title' => Yii::t ( 'app', 'Update' ),
								'url' => [ 
										'update',
										'id' => $model->id 
								] 
							 
						);
						$this->menu ['delete'] = array (
								'label' => '<span class="glyphicon glyphicon-trash"></span>',
								'title' => Yii::t ( 'app', 'Delete' ),
								'url' => [ 
										'delete',
										'id' => $model->id 
								],
								'htmlOptions' => [ 
										'data-method' => 'post',
										'data-confirm' => 'Are you sure you want to delete this item?' 
								],
								'visible' => User::isAdmin () 
						);
					}
				}
		}
	}
}
