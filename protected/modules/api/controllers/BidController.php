<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Bid;
use yii;
use app\modules\api\controllers\ApiTxController;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Notification;
use app\models\Job;

/**
 * BidController implements the API actions for Bid model.
 */
class BidController extends ApiTxController {
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
												'place-bid',
												'cancel-bid',
												'get-bid',
												'bid-list',
												'bid-list-worker',
												'past-bid' 
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
												'bid-list',
												'get-bid',
												'bid-list',
                                                'bid-list-worker'
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
	public function actionPastBid() {
		$data = [ ];
		$model = Bid::find ()->where ( [ 
				'created_by_id' => \yii::$app->user->id 
		] )->andFilterWhere ( [ 
				'type_id' => Bid::TYPE_AWARD
		] )->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asCustomer ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['list'] = $list;
			}
		} else {
			$data ['error'] = 'No Past Bid Found';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionBidListWorker() {
		$data = [ ];
        $query = Bid::find ()->alias('b')->joinWith('job as j')->where([
            'b.created_by_id' => \yii::$app->user->id,
            'b.type_id' => Bid::TYPE_PROGRESS
        ]);
        $query->andWhere([
            'j.state_id'=>Job::STATE_IN_BID_PROGRESS
        ]);
        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ] );

		if (count ( $dataProvider->models ) > 0) {
			
			$list = [ ];
			foreach ( $dataProvider->models as $mod ) {
				
				$list [] = $mod->asCustomer ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $list;
			}
		} else {
			$data ['error'] = 'No Biding Found';
		}
		return $this->sendResponse ( $data );
	}

    public function actionCancelBid($bid_id) {
		$data = [ ];
		$model = Bid::find ()->where ( [ 
				'id' => $bid_id
		] )->one ();
		if (! empty ( $model )) {
			$model->delete ();
			$data ['status'] = self::API_OK;
			$data ['Message'] = 'Bid has been canceled';
		} else {
			$data ['error'] = 'No Bid Found';
		}
		
		return $this->sendResponse ( $data );
	}
	/**
	 * Lists all Bid models.
	 *
	 * @return mixed
	 */
	public function actionIndex($page = null) {
		$data = [ ];
		$list = [ ];
		$params = \Yii::$app->request->bodyParams;
		$query = Bid::find ();
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '20',
						'page' => $page 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		if (count ( $dataProvider->models ) > 0) {
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asJson ();
			}
			$data ['status'] = self::API_OK;
			$data ['detail'] = $list;
			$data ['pageCount'] = isset ( $page ) ? $page : '0';
			$data ['totalPage'] = isset ( $dataProvider->pagination->pageCount ) ? $dataProvider->pagination->pageCount : '0';
		} else {
			$data ['error'] = \yii::t ( 'app', 'Not Found' );
		}
		
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Displays a single app\models\Bid model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\Bid" );
	}
	
	/**
	 * Creates a new Bid model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionPlaceBid() {
		$data = [ ];
		$model = new Bid ();
		$userModel = \Yii::$app->user->identity;
		$job = Job::find ()->where ( [ 
				'id' => $_POST ['Bid'] ['job_id'] 
		] )->one ();
		if ($model->load ( Yii::$app->request->post () )) {
			if ($job->state_id == Job::STATE_IN_BID_PROGRESS) {
				$user_details = Bid::find ()->where ( [ 
						'created_by_id' => $userModel->id,
						'job_id' => $_POST ['Bid'] ['job_id'] 
				] )->one ();
				if (empty ( $user_details )) {
				    $model->created_by_id = $userModel->id;
                    $model->type_id = Bid::TYPE_PROGRESS;
					if ($model->save ()) {
						$data ['status'] = self::API_OK;
						$data ['detail'] = $model;
						$data ['message'] = 'New Bid has been place for the Job';
						/*
						 * if (! empty ( $job )) {
						 * Notification::notification ( new Job (), $data ['message'], $job->id, $job->created_by_id );
						 * }
						 */
					} else {
						$data ['error'] = $model->getErrorsString ();
					}
				} else {
					$data ['error'] = 'You already save Bid on this Job';
				}
			} else {
				$data ['error'] = 'Job already has been Awarded';
			}
		} else {
			$data ['error_post'] = 'No Data Posted';
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Updates an existing Bid model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	/*
	 * public function actionUpdate($id) {
	 * $data = [ ];
	 * $model = $this->findModel ( $id );
	 * if ($model->load ( Yii::$app->request->post () )) {
	 *
	 * if ($model->save ()) {
	 *
	 * $data ['status'] = self::API_OK;
	 *
	 * $data ['detail'] = $model;
	 * } else {
	 * $data ['error'] = $model->flattenErrors;
	 * }
	 * } else {
	 * $data ['error_post'] = 'No Data Posted';
	 * }
	 *
	 * return $this->sendResponse ( $data );
	 * }
	 */
	public function actionUpdate($id) {
		$data = [ ];
		$user = Yii::$app->user->id;
		$params = \Yii::$app->request->bodyParams;
		
		$bidModel = Bid::find ()->where ( [ 
				'id' => $id
		] )->one ();
		if (! empty ( $bidModel )) {
			if ($bidModel->load ( $params )) {
				if ($bidModel->save ()) {
					$data ['status'] = self::API_OK;
					$data ['detail'] = $bidModel->asJson ();
				} else {
					$data ['error'] = $bidModel->getErrors ();
				}
			} else {
				$data ['error'] = 'No Data Posted';
			}
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Deletes an existing Bid model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionDelete($id, $page) {
		return $this->txDelete ( $id, "app\models\Bid" );
	}
	/**
	 * Bid List
	 *
	 * @return mixed
	 */
	public function actionGetBid($id, $page = null) {
		$data = [ ];
		
		$model = Bid::find ()->where ( [ 
				'job_id' => $id
		] );
		
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $model,
				'pagination' => [ 
						'pageSize' => '20',
						'page' => $page 
				],
				
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		if (count ( $dataProvider->models ) > 0) {
			foreach ( $dataProvider->models as $mod ) {
				
				$data ['list'] [] = $mod->asJson ( true );
			}
			$data ['status'] = self::API_OK;
			$data ['pageCount'] = isset ( $page ) ? $page : '0';
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['totalPage'] = isset ( $dataProvider->pagination->pageCount ) ? $dataProvider->pagination->pageCount : '0';
		} else {
			$data ['error'] = 'No Bid Found';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionBidList($job_id, $page = null) {
		$data = [ ];

		$query = Bid::find ()->where ( [
				'job_id' => $job_id 
		] )->andFilterWhere ( [ 
				'!=',
				'type_id',
				Bid::TYPE_AWARD 
		] );
        $dataProvider = new \yii\data\ActiveDataProvider ( [
            'query' => $query,
            'pagination' => [
                'pageSize' => '20',
                'page' => $page
            ],

            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ] );
        if (count ( $dataProvider->models ) > 0) {
            $list = [ ];
            foreach ( $dataProvider->models as $mod ) {
                $list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $list;
			}
		} else {
			$data ['error'] = 'No Booking Found';
		}
		return $this->sendResponse ( $data );
	}
}