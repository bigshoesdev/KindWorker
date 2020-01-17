<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Country;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;
use app\models\City;
use app\models\State;


/**
 * CountryController implements the API actions for Country model.
 */
class CountryController extends ApiTxController {
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
												'get-state',
												'get-city',
												'get-city-approved'
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
												'update' ,
												'get-state',
												'get-city',
												'get-city-approved'
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
	 * Lists all Country models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$data = [ ];
		
		$model = Country::find ()->where([
            'type_id' => Country::TYPE_APPROVED
		    ])->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $list;
			} else {
				$data ['error'] = 'Nothing Found';
			}
		} else {
			$data ['error'] = 'No State Found';
		}
		
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Displays a single app\models\Country model.
	 * 
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\Country" );
	}
	
	/**
	 * Creates a new Country model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionAdd() {
		return $this->txSave ( "app\models\Country" );
	}
	
	/**
	 * Updates an existing Country model.
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
	public function actionGetState($id) {
		$data = [ ];
		
		$model = State::find ()->where ( [ 
				'country_id' => $id 
		] )->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $list;
			} else {
				$data ['error'] = 'Nothing Found';
			}
		} else {
			$data ['error'] = 'No State Found';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionGetCity($id = null) {
		$data = [ ];
		$ids = [];
		$states = State::find()->where([
		    'country_id' => $id
        ])->all();
        if(!empty($states)){
            foreach ( $states as $state ) {
                $ids [] = $state->id;
            }
        }

		if (!empty ( $ids )) {
            $model = City::find ()->where([
                'type_id' => City::TYPE_APPROVED
            ])->andWhere ( [
                'in',
                'state_id',
                $ids
            ])->all();
            if (! empty ( $model )) {
                $list = [ ];
                foreach ( $model as $mod ) {
                    $list [] = $mod->asJson ();
                }
                if (! empty ( $list )) {
                    $data ['status'] = self::API_OK;
                    $data ['detail'] = $list;
                } else {
                    $data ['error'] = 'Nothing Found';
                }
            } else {
                $data ['error'] = 'No State Found';
            }
		} else {
            $data ['error'] = 'Nothing Found';
		}
		return $this->sendResponse ( $data );
	}
	public function actionGetCityApproved() {
		$data = [ ];
		
		$model = City::find ()->where ( [ 
				'type_id' => City::TYPE_APPROVED 
		] )->all ();
		
			if (! empty ( $model )) {
				$list = [ ];
				foreach($model as $models){
					$list [] = $models->asJson ();
				}
			
				if (! empty ( $list )) {
					$data ['status'] = self::API_OK;
					$data ['detail'] = $list;
				} else {
					$data ['error'] = 'Nothing Found';
				}
			} else {
				$data ['error'] = 'No State Found';
			}
			
			return $this->sendResponse ( $data );
		
		
	}
	/**
	 * Deletes an existing Country model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @return mixed
	 */
	public function actionDelete($id) {
		return $this->txDelete ( $id, "app\models\Country" );
	}
}
