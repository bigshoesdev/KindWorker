<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\User;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Slot;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;

/**
 * SlotController implements the API actions for Slot model.
 */
class SlotController extends ApiTxController {
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
												'set',
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
												'set',
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
	 * Lists all Slot models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$data = [];
		$model = Slot::find()->all();
		if(!empty($model)){
			$list = [];
			foreach ($model as $mod){
				$list [] = $mod->asJson();
			}
			if(!empty($list)){
				$data['status'] = self::API_OK;
				$data['detail'] = $list;
			}
		}else{
			$data['error'] = 'Nothing Found';
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Displays a single app\models\Slot model.
	 * 
	 * @return mixed
	 */
	public function actionGet() {
        //return $this->txget ( $id, "app\models\Slot" );
	    $data = [];
	    $user = User::find()->where([
	        'id' => \yii::$app->user->id
        ])->one();
	    $models = Slot::find()->where([
	        'created_by_id' => \yii::$app->user->id
        ])->orderBy('day_id ASC')->all();
		if(empty($models) || empty($user)){
		    $data['detail'] = [];
        } else{
		    $list1 = [];
		    $list = [];
		    foreach ($models as $model){
		        $list[] = $model->asJson();
            }
            $list1['vacation_mode'] = $user->vacation_mode;
            $list1['slot_list'] = $list;
            $data['status'] = self::API_OK;
            $data['detail'] = $list1;
        }
        return $this->sendResponse ( $data );
	}

    public function actionSet() {
        //return $this->txget ( $id, "app\models\Slot" );
        $data = [];
        $flag = false;
        $params = \yii::$app->request->post ();
        if (empty ( $params )) {
            $data ['error'] = 'No Data Posted';
            return $this->sendResponse ( $data );
        }
        $user = User::find()->where([
            'id' => \yii::$app->user->id
        ])->one();
        if(empty($user)){
            $data ['error'] = 'User is not found.';
            return $this->sendResponse ( $data );
        }
        $alreadyExist = Slot::find()->where([
            'created_by_id' => \yii::$app->user->id
        ])->all();
        if (! empty ( $alreadyExist )) {
            foreach ( $alreadyExist as $del ) {
                if ($del->delete ()) {
                    $flag = true;
                } else {
                    $flag = false;
                    break;
                }
            }
        } else {
            $flag = true;
        }
        if($flag == true){
            $vacation_mode = isset($params['User']['vacation_mode'])?$params['User']['vacation_mode']:User::VACATION_OFF;
            $user->vacation_mode = $vacation_mode;
            $user->save();
            $data = isset ( $params ['Slot'] ) ? $params ['Slot'] : [ ];
            $data = json_decode ( $data, true );
            if (! empty ( $data )) {
                foreach ($data as $materials) {
                    $materialModel = new Slot ();
                    $materialModel->times = $materials ['times'];
                    $materialModel->type_id = $materials ['type_id'];
                    $materialModel->day_id = $materials ['day_id'];
                    $materialModel->created_by_id = \yii::$app->user->id;
                    if (!$materialModel->save()) {
                        $data ['error'] = $materialModel->getErrorsString();
                        return $this->sendResponse($data);
                    }
                }
                $data['status'] = self::API_OK;
                $data['message'] = 'Your slots have been saved successfully';
            } else {
                $data ['error'] = 'Slots detail not formated.';
            }
        } else {
            $data ['error'] = \yii::t ( 'app', 'Something Went Wrongs' );
        }
        return $this->sendResponse ( $data );
    }
	
	/**
	 * Creates a new Slot model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionAdd() {
		return $this->txSave ( "app\models\Slot" );
	}
	
	/**
	 * Updates an existing Slot model.
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
     * Deletes an existing Slot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete($id)
    {
    return $this->txDelete( $id,"app\models\Slot" );
       
    }

   
}
