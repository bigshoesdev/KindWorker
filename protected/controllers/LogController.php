<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;
use Yii;
use app\models\Log;
use app\models\search\Log as LogSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use Codeception\Lib\Interfaces\Queue;
/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends TController
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
												'custom-error',
												'view',
												'delete',
												'mass',
										],
										'allow' => true,
										'roles' => [
												'@'
										]
								],
								[
										'actions' => [

												'custom-error',
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
								],
						]
				]
		];
	}


    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;
 		//$this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionDelete($id) {
    	$model = $this->findModel ( $id );
    	
    	$model->delete ();
    	return $this->redirect ( [
    			'index'
    	] );
    } 
    public function actionCustomError() {
    	$exception = Yii::$app->errorHandler->exception;
    	
    	if ($exception !== null) {
    		
    		$log = new Log ();
    		
    		$log->link = $exception->statusCode . ":  " . \yii::$app->request->url;
    		$log->error = $exception->getMessage ();
    		$log->description = $exception->getTraceAsString ();
    		
    		if (\Yii::$app->controller->module->id == 'api') {
    			$log->type_id = Log::TYPE_API;
    		} else {
    			$log->type_id = Log::TYPE_WEB;
    		}
    		$log->save ();
    		
    		if ($module->emailClass) {
    			$class = $module->emailClass;
    			
    			try {
    				$emailQueue = new $class ();
    				if (! $module->emails) {
    					throw new yii\base\InvalidConfigException ( "Emails property must be set" );
    				}
    				
    				if (! is_array ( $module->emails )) {
    					throw new \yii\base\InvalidConfigException ( "Emails property must be an array." );
    				}
    				
    				if ($emailQueue instanceof Queue) {
    					$sub = $exception->statusCode . ":  " . \yii::$app->request->url;
    					foreach ( $module->emails as $email )
    						$emailQueue->sendNow ( $email, $exception->getTraceAsString (), null, $sub );
    				} else {
    					throw new \yii\base\InvalidConfigException ( "Email class must implement Queue interface" );
    				}
    			} catch ( \Exception $exception ) {
    				
    				return $this->render ( 'error', [
    						'message' => $exception->getTraceAsString (),
    						'name' => $exception->getMessage (),
    						'status' => $exception->statusCode
    				] );
    			}
    		}
    		
    		return $this->render ( 'error', [
    				'message' => $exception->getTraceAsString (),
    				'name' => $exception->getMessage (),
    				'status' => $exception->statusCode
    		] );
    	}
    }
    
    

    /**
     * Displays a single Log model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', ['model' => $model]);

    }
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {

			if (! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function updateMenuItems($model = null) {
    	switch (\Yii::$app->controller->action->id) {
    		
    		default :
    		
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
