<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\City;
use app\models\search\City as CitySearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends TController
{
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
												'mass',
										],
										'allow' => true,
										'roles' => [
												'@'
										]
								],
								[
										'actions' => [

												'view',
										],
										'allow' => true,
										'roles' => [
												'?',
												'*'
										]
								]
						]
				],
				'verbs' => [
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [
								'delete' => [
										'post'
								],
						]
				]
		];
	}


    /**
     * Lists all City models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 		$this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', ['model' => $model]);

    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new City();
        $model->loadDefaultValues();
        //$model->state_id = City::STATE_ACTIVE;
		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
        if ($model->load($post) ) {
        	$model->type_id = City::TYPE_APPROVED;
        	if( $model->save()){
        		
        		return $this->redirect(['view', 'id' => $model->id]);
        	}
       
        }
        $this->updateMenuItems();
        return $this->render('add', [
                'model' => $model,
            ]);

    }
 
    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

 		$post = \yii::$app->request->post ();
		if (\yii::$app->request->isAjax && $model->load ( $post )) {
			\yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
        if ($model->load($post) ) {
        	if($model->save())
        	{
        		return $this->redirect(['view', 'id' => $model->id]);
        	}
        	else {
        	print_r($model->getErrors());exit;
        	}
           
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
                'model' => $model,
            ]);

    }

    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

		$model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = City::findOne($id)) !== null) {

			if ($accessCheck && ! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
							],
						//	'visible' => User::isAdmin ()
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
						//	'visible' => User::isAdmin ()
					); 
				}
				break;
			case 'update' :
				{
					/* $this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-plus"></span>',
							'title' => Yii::t ( 'app', 'add' ),
							'url' => [
									'add'
							],
						//	'visible' => User::isAdmin ()
					); */
					$this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [
									'index'
							],
						//	'visible' => User::isAdmin ()
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
							],
						//	'visible' => User::isAdmin ()
					);
					if ($model != null)
					{
						$this->menu ['update'] = array (
								'label' => '<span class="glyphicon glyphicon-pencil"></span>',
								'title' => Yii::t ( 'app', 'Update' ),
								'url' => [
										'update',
										'id' => $model->id
								],
						//		'visible' => User::isAdmin ()
						);
						/* $this->menu ['delete'] = array (
								'label' => '<span class="glyphicon glyphicon-trash"></span>',
								'title' => Yii::t ( 'app', 'Delete' ),
								'url' => [
										'delete',
										'id' => $model->id
								]
						//	 'visible' => User::isAdmin ()
						); */
					}
				}
		}

	}
}
