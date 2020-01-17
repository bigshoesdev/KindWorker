<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Category;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;
use app\models\SubCategory;
use app\models\Service;
use app\models\AvailabilitySlot;

/**
 * CategoryController implements the API actions for Category model.
 */
class CategoryController extends ApiTxController {
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
												'sub-category',
												'category',
												'add-service',
												'search-service',
												'with-sub-category' 
										
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
												'sub-category',
												'category',
												'search-service',
												'with-sub-category' 
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
	 * Lists all Category models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\Category" );
	}
	
	/**
	 * Displays a single app\models\Category model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\Category" );
	}
	
	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionSubCategory($id) {
		$data = [ ];
		$models = SubCategory::find ()->where ( [ 
				'category_id' => $id 
		] )->all ();
		
		if ($models) {
			foreach ( $models as $model ) {
				$data ['list'] [] = $model->asJson ( true );
			}
			$data ['status'] = self::API_OK;
		} else {
			
			$data ['error'] = "Not Found";
		}
		return $this->sendResponse ( $data );
	}
	public function actionCategory() {
		$localarray = [ ];
		$onlinearray = [ ];
		$data = [ ];
//		$onlineModel = Category::find ()->where ( [
//				'type_id' => Category::ONLINE_SERIVICE
//		] )->all ();
//		$localModel = Category::find ()->where ( [
//				'type_id' => Category::LOCAL_SERVICE
//		] )->all ();

        $onlineModel = Category::find ()->where ( [
            'type_id' => Category::ONLINE_SERIVICE
        ] )->orderBy ( [
            'id' => SORT_DESC
        ] )->all ();
        $localModel = Category::find ()->where ( [
            'type_id' => Category::LOCAL_SERVICE
        ] )->orderBy ( [
            'id' => SORT_DESC
        ] )->all ();

        if ((! empty ( $onlineModel ) && ! empty ( $localModel ))) {
			$data ['status'] = self::API_OK;
			foreach ( $localModel as $local ) {
				$localarray [] = $local->asJson ();
			}
			foreach ( $onlineModel as $online ) {
				$onlinearray [] = $online->asJson ();
			}
			$data ['detail'] ['local'] = $localarray;
			$data ['detail'] ['online'] = $onlinearray;
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionWithSubCategory() {
		$localarray = [ ];
		$onlinearray = [ ];
		$data = [ ];
		$onlineModel = Category::find ()->where ( [ 
				'type_id' => Category::ONLINE_SERIVICE 
		] )->all ();
		$localModel = Category::find ()->where ( [ 
				'type_id' => Category::LOCAL_SERVICE 
		] )->all ();
		if ((! empty ( $onlineModel ) && ! empty ( $localModel ))) {
			$data ['status'] = self::API_OK;
            foreach ( $localModel as $local ) {
				$localarray [] = $local->asJson ( true );
			}
			foreach ( $onlineModel as $online ) {
				$onlinearray [] = $online->asJson ( true );
			}
			$data ['detail'] ['local'] = $localarray;
			$data ['detail'] ['online'] = $onlinearray;
		}
//		$result_data=[];
//		$result_data['url'] = 'api/category/with-sub-category';
//		$result_data['status'] = 'success';
//      return json_encode($result_data);
        return $this->sendResponse ( $data );
    }
	public function actionAddService() {
		$data = [ ];
		$params = \Yii::$app->request->bodyParams;
		
		$model = new Service ();
		
		if ($model->load ( $params )) {
			
			if ($model->save ()) {
				
				if ($model->rate_type == Service::HOURLY) {
					$data = isset ( $params ['AvailabilitySlot'] ) ? $params ['AvailabilitySlot'] : [ ];
					$data = json_decode ( $data, true );
					if (! empty ( $data )) {
						foreach ( $data as $materials ) {
							if (! empty ( $materials )) {
								$materialModel = new AvailabilitySlot ();
								$materialModel->day = $materials ['day'];
								$materialModel->from_time = $materials ['from_time'];
								$materialModel->to_time = $materials ['to_time'];
								$materialModel->service_id = $model->id;
								
								if ($materialModel->save ()) {
									$data ['status'] = 'OK';
									$data ['message'] = 'AvailabilitySlot has been Added Successfully';
								} else {
									$data ['error'] = $materialModel->getErrorsString ();
								}
							} else {
								$data ['error'] = 'AvailabilitySlot detail not formated.';
							}
						}
					}
				}
				
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ();
			} else {
				$data ['error'] = $model->getErrorsString ();
			}
		} else {
			$data ['error'] = 'Not Data Posted';
		}
		return $this->sendResponse ( $data );
	}
	public function actionSearchService() {
		$data = [ ];
		$searchModel = new \app\models\search\Service ();
		
		$search = '';
		$type = '';
		$params = \Yii::$app->request->bodyParams;
		
		if (isset ( $params ['Category'] ['search'] ) && ! empty ( $params ['Category'] ['search'] )) {
			$search = $params ['Category'] ['search'];
		}
		
		if (isset ( $params ['Category'] ['type'] ) && ! empty ( $params ['Category'] ['type'] )) {
			$type = $params ['Category'] ['type'];
		}
		
		$dataProvider = $searchModel->searchService ( \yii::$app->request->post () );
		
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asSearch ();
				$data ['list'] = $list;
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = "Not Found";
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionSearchWorker() {
		$data = [ ];
		$searchModel = new \app\models\search\Service ();
		
		$search = '';
		$zip = '';
		$type = '';
		$params = \Yii::$app->request->bodyParams;
		
		if (isset ( $params ['Category'] ['search'] ) && ! empty ( $params ['Category'] ['search'] )) {
			$search = $params ['Category'] ['search'];
		}
		if (isset ( $params ['Category'] ['zip'] ) && ! empty ( $params ['Category'] ['zip'] )) {
			$zip = $params ['Category'] ['zip'];
		}
		if (isset ( $params ['Category'] ['type'] ) && ! empty ( $params ['Category'] ['type'] )) {
			$type = $params ['Category'] ['type'];
		}
		
		$dataProvider = $searchModel->searchService ( \yii::$app->request->post () );
		
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asSearch (  );
				$data['list'] = $list;
			}
			$data['status'] = self::API_OK;
		} else {
			$data['error'] = "Not Found";
		}
		
		return $this->sendResponse ( $data );
	}
}
