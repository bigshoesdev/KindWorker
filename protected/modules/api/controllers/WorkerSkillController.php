<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\WorkerSkill;
use app\modules\api\controllers\ApiTxController;
use phpDocumentor\Reflection\Types\Integer;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;

/**
 * WorkerSkillController implements the API actions for WorkerSkill model.
 */
class WorkerSkillController extends ApiTxController {
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
												'search-worker',
												'filter-worker',
												'search-worker-local',
												'worker-detail',
												'state-change',
												'view',
												'add-skills' 
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
												'worker-detail',
                                                'search-worker',
                                                'filter-worker',
                                                'search-worker-local',
												'worker-detail' 
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
	public function actionAddSkills() {
		$data = [ ];
		$flag = false;
		
		$params = \yii::$app->request->post ();
		if (empty ( $params )) {
			$data ['error'] = 'No Data Posted';
			return $this->sendResponse ( $data );
		}
		$alreadyExist = WorkerSkill::find ()->where ( [ 
				'created_by_id' => \yii::$app->user->id 
		] )->all ();
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
		
		if ($flag == true) {
			$data = isset ( $params ['WorkerSkill'] ) ? $params ['WorkerSkill'] : [ ];
			$data = json_decode ( $data, true );
			if (! empty ( $data )) {
				foreach ( $data as $materials ) {
					if (! empty ( $materials )) {
						$materialModel = new WorkerSkill ();
						$materialModel->hourly_rate = $materials ['hourly_rate'];
						$materialModel->sub_category_id = $materials ['sub_category_id'];
						$materialModel->category_id = $materials ['category_id'];
						$materialModel->type_id = $materials ['type_id'];
						$materialModel->experience = $materials ['experience'];
                        $materialModel->travel_me = isset($materials ['travel_me'])?$materials ['travel_me']:0;
                        $materialModel->travel_customer = isset($materials ['travel_customer'])?$materials ['travel_customer']:0;
                        $materialModel->delivery_time = isset ( $materials ['delivery_time'] ) ? ($materials ['delivery_time']) : '';
						$materialModel->description = $materials ['description'];
						if ($materialModel->save ()) {
							$data ['status'] = self::API_OK;
							$data ['message'] = 'Worker Skills has been Added Successfully';
						} else {
							$data ['error'] = $materialModel->getErrorsString ();
						}
					} else {
						$data ['error'] = 'Worker Skills detail not formated.';
					}
				}
			} else {
				$data ['error'] = \yii::t ( 'app', 'Json Format is Wrong' );
			}
		} else {
			$data ['error'] = \yii::t ( 'app', 'Something Went Wrongs' );
		}
		return $this->sendResponse ( $data );
	}

    public function actionAdd() {
        $data = [ ];
        $flag = false;

        $param = \yii::$app->request->post ();
        if (empty ( $param )) {
            $data ['error'] = 'No Data Posted';
            return $this->sendResponse ( $data );
        }
        $alreadyExist = WorkerSkill::find ()->where ( [
            'created_by_id' => \yii::$app->user->id,
            'sub_category_id' => $param['WorkerSkill']['sub_category_id']
        ] )->all ();
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

        if ($flag == true) {
            $skill = new WorkerSkill ();
            $skill->state_id = WorkerSkill::STATE_ACTIVE;
            $skill->hourly_rate = isset($param ['WorkerSkill']['hourly_rate'])?$param ['WorkerSkill']['hourly_rate']:'0.0';
            $skill->sub_category_id = intval($param ['WorkerSkill']['sub_category_id']);
            $skill->category_id = intval($param ['WorkerSkill']['category_id']);
            $skill->type_id = intval($param ['WorkerSkill']['type_id']);
            $skill->travel_me = isset($param ['WorkerSkill']['travel_me'])?$param ['WorkerSkill']['travel_me']:0;
            $skill->travel_customer = isset($param ['WorkerSkill']['travel_customer'])?$param ['WorkerSkill']['travel_customer']:0;
            $skill->experience = isset($param ['WorkerSkill']['experience'])?intval($param ['WorkerSkill']['experience']):0;
            $skill->delivery_time = isset ( $param ['WorkerSkill']['delivery_time'] ) ? ($param ['WorkerSkill']['delivery_time']) : '';
            $skill->description = isset($param ['WorkerSkill']['description'])?$param ['WorkerSkill']['description']:'';
            if ($skill->save ()) {
                $data ['status'] = self::API_OK;
                $data ['detail'] = $skill->asJson();
                $data ['message'] = 'New Skill has been Added Successfully';
            } else {
                $data ['error'] = $param->getErrorsString ();
            }
        } else {
            $data ['error'] = \yii::t ( 'app', 'Something Went Wrongs' );
        }
        return $this->sendResponse ( $data );
    }

	public function actionView($id) {
		$data = [ ];
		$model = WorkerSkill::find ()->where ( [ 
				'id' => $id
		] )->one ();
		;
		if (! empty ( $model )) {
			$data ['status'] = self::API_OK;
			$data ['detail'] = $model->asJson();
		} else {
			$data ['error'] = 'No Skill Found';
		}
		
		return $this->sendResponse ( $data );
	}
	/**
	 * Inactive an existing WorkerSkill model.
	 *
	 * @return mixed
	 */
	public function actionStateChange($id) {
		$data = [ ];
		$model = WorkerSkill::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		if (! empty ( $model )) {
			if ($model->state_id == WorkerSkill::STATE_INACTIVE) {
				$model->state_id = WorkerSkill::STATE_ACTIVE;
			} else {
				$model->state_id = WorkerSkill::STATE_INACTIVE;
			}
			
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ();
			}
		} else {
			$data ['error'] = 'No Skill Found';
		}
		
		return $this->sendResponse ( $data );
	}
	/**
	 * Lists all WorkerSkill models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\WorkerSkill" );
	}
	
	/**
	 * Displays a single app\models\WorkerSkill model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\WorkerSkill" );
	}
	
	/**
	 * Updates an existing WorkerSkill model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$data = [ ];
		$model = $model = WorkerSkill::find ()->where ( [
            'id' => $id
        ] )->one ();
		if(empty($model))
        {
            $model = new WorkerSkill();
        }
		if ($model->load ( \Yii::$app->request->post () )) {
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson();
			} else {
				$data ['error'] = $model->getErrorsString();
			}
		} else {
			$data ['error'] = 'No Data Posted';
		}
		
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Deletes an existing WorkerSkill model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionDelete($id) {
		$data = [ ];
		$model = WorkerSkill::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		if (! empty ( $model )) {
			if($model->delete ())
            {
                $data ['status'] = self::API_OK;
                $data ['detail'] = 'Your Skill has been deleted Successfully';
            } else{
                $data ['error'] = 'Your Skill can not delete';
            }
		} else {
			$data ['error'] = 'No Skill Found';
		}
		
		return $this->sendResponse ( $data );
	}

    public function actionFilterWorker($page = null) {
        $data = [ ];
        $searchModel = new \app\models\search\WorkerSkill ();

        $dataProvider = $searchModel->filterWorkerSkill ( \yii::$app->request->bodyParams, $page );
        if ($dataProvider->models) {
            $data ['pageSize'] = $dataProvider->pagination->pageSize;
            $data ['pageCount'] = $dataProvider->pagination->pageCount;

            foreach ( $dataProvider->models as $model ) {
                $list [] = $model->asSearch ();

                $data ['list'] = $list;
            }
            $data ['status'] = self::API_OK;
        }else {
            $data ['error'] = "Worker Not Found";
        }

        return $this->sendResponse ( $data );
    }

	public function actionSearchWorker($page = null)
    {
        $data = [];
        $searchModel = new \app\models\search\WorkerSkill ();

		$dataProvider = $searchModel->searchWorkerSkill ( \yii::$app->request->bodyParams, $page );
        if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asSearch ();
				
				$data ['list'] = $list;
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = "Worker Not Found";
		}
		
		return $this->sendResponse ( $data );
	}

	public function actionSearchWorkerLocal($page = null, $lat, $long) {
		$data = [ ];
		$searchModel = new \app\models\search\WorkerSkill ();
		$params = \Yii::$app->request->bodyParams;
		$user = User::find ()->where ( [ 
				'id' => \yii::$app->user->id 
		] )->one ();
		if (! empty ( $user )) {
			$user->last_zip = $params ['User'] ['last_zip'];
			$user->last_search = $params ['User'] ['last_search'];
			$user->last_latitude = $lat;
			$user->last_longitude = $long;
			$user->save ();
		}

		$dataProvider = $searchModel->searchWorkerSkillLocal ( \yii::$app->request->bodyParams, $page, $lat, $long );
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asSearch ();
				
				$data ['list'] = $list;
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = "Worker Not Found";
		}
		
		return $this->sendResponse ( $data );
	}
//	public function actionWorkerDetail($lat,$long) {
//		$data = [ ];
//		$params = \Yii::$app->request->bodyParams;
//		$id = '';
//		$sub_category_id = '';
//		if (isset ( $params ['WorkerSkill'] ['id'] ) && ! empty ( $params ['WorkerSkill'] ['id'] )) {
//			$id = $params ['WorkerSkill'] ['id'];
//		}
//
//		if (isset ( $params ['WorkerSkill'] ['sub_category_id'] ) && ! empty ( $params ['WorkerSkill'] ['sub_category_id'] )) {
//			$sub_category_id = $params ['WorkerSkill'] ['sub_category_id'];
//		}
//
//		$model = WorkerSkill::find ()->where ( [
//				'id' => $id,
//				'sub_category_id' => $sub_category_id
//		] )->one ();
//
//		if ((! empty ( $model ))) {
//			$data ['status'] = self::API_OK;
//			$data ['detail'] = $model->asSearch ($lat,$long);
//		} else {
//			$data ['error'] = 'User not found';
//		}
//
//		return $this->sendResponse ( $data );
//	}

    /**
     * Worker Details api for worker side
     *
     * @return mixed
     */
    public function actionWorkerDetail($id) {
        $data = [ ];
        $model = User::find ()->where ( [
            'id' => $id
        ] )->one ();
        if (! empty ( $model )) {
            $data ['status'] = self::API_OK;
            $data ['details'] = $model->asWorkerJson ();
        } else {
            $data ['error'] = 'No worker Found';
        }
        return $this->sendResponse ( $data );
    }

}
