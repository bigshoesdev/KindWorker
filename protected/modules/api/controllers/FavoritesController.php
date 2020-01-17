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
use app\models\Favorites;
use app\models\Job;
use app\models\SubCategory;
use app\models\Service;
use app\models\AvailabilitySlot;

/**
 * CategoryController implements the API actions for Category model.
 */
class FavoritesController extends ApiTxController {
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
    public function actionGet($page=null) {
        $data = [ ];
        $list = [ ];
        $user = \yii::$app->user->id;
        $query = Favorites::find ()->where ( [
                'created_by_id' => $user,
            ]);
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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd($id) {
        $data = [ ];
        $user = \yii::$app->user->id;
        $model = Favorites::find ()->where ( [
            'created_by_id' => $user,
            'job_id' => $id
        ] )->one ();
        if($model==null)
        {
            $model = new Favorites();
            $model->job_id = $id;
            $model->created_by_id = $user;
        }
        $job = Job::find()->where([
            'id'=>$id
        ])->one();
        if($job==null)
        {
            $data ['error'] = 'Worker is not exist!';
            return $this->sendResponse ( $data );
        }
        $model->worker_id = $job->worker_id;
        if ($model->save()) {
            $data ['result'] = 'Favorites add successed!';
            $data ['status'] = self::API_OK;
        } else {
            $data ['error'] = $model->errorsString;
        }
        return $this->sendResponse ( $data );
    }
    public function actionDelete($id) {
        $data = [ ];
        $user = \yii::$app->user->id;
        $model = Favorites::find ()->where ( [
            'created_by_id' => $user,
            'job_id' => $id
        ] )->one ();
        if(!empty($model))
        {
            if($model->delete ())
            {
                $data ['result'] = 'Favorites delete successed!';
                $data ['status'] = self::API_OK;
            }
            else{
                $data ['error'] = $model->errorsString;
            }
        }
        else
        {
            $data ['error'] = 'Favorites delete failed';
        }
        return $this->sendResponse ( $data );
    }

}
