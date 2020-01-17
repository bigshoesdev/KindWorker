<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Rating;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;
use app\models\Job;
use app\models\Notification;

/**
 * RatingController implements the API actions for Rating model.
 */
class RatingController extends ApiTxController {
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
												'delete' ,
												'view'
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
	 * Lists all Rating models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\Rating" );
	}
	
	/**
	 * Displays a single app\models\Rating model.
	 * 
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\Rating" );
	}
	/**
	 * Creates a new Rating model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionAdd() {
		$data = [];
		$model = new Rating();
		$params = \yii::$app->request->post();
		$job = Job::find()->where(['id' => $params['Rating']['model_id']])->one();
		if($model->load($params)){
			if($model->save()){
				$data['status'] = self::API_OK;
				$data['detail'] = $model->asJson();
				$data ['message'] = \yii::$app->user->identity->email.' Has rated on your Job';
				 if(!empty($job)){
					Notification::notification ( new Job(), $data ['message'], $job->id,$params['Rating']['model_id'] );
				} 
			
			}else{
				$data['error'] = $model->getErrorsString();
			}
		}else{
			$data['error'] = 'No Data Posted';
		}
		return $this->sendResponse($data);
	}
	
	public function actionView($id){
		$data = [];
		$model = Rating::find()->where(['user_id' => $id])->all();
		if(!empty($model)){
			$list =[];
			foreach ($model as $mod){
				$list[] = $mod->asJson();
			}
			if(!empty($list)){
				$data['status'] = self::API_OK;
				$data['list'] = $list;
			}
		}
		return $this->sendResponse($data);
	}
	
	/**
	 * Updates an existing Rating model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$data = [ ];
		$model = $this->findModel ( $id );
		if ($model->load ( \Yii::$app->request->post () )) {
			
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
     * Deletes an existing Rating model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete($id)
    {
    return $this->txDelete( $id,"app\models\Rating" );
       
    }

   
}
