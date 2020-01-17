<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\Budget;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;
use app\models\AvailabilitySlot;

/**
 * BudgetController implements the API actions for Category model.
 */
class BudgetController extends ApiTxController {
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
                            'get'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'get'
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
     * Lists all Budget models.
     *
     * @return mixed
     */
    public function actionIndex() {
        $data = [ ];

        $model = Budget::find ()->all ();
        if (! empty ( $model )) {
            $list1 = [ ];
            $model1 = Budget::find()->where([
                'state_id'=>0
            ])->all();
            foreach ( $model1 as $mod ) {
                $list1 [] = $mod->asJson ();
            }
            $list2 = [ ];
            $model2 = Budget::find()->where([
                'state_id'=>1
            ])->all();
            foreach ( $model2 as $mod ) {
                $list2 [] = $mod->asJson ();
            }
            if (! empty ( $list1 ) || !empty($list2)) {
                $data ['status'] = self::API_OK;
                $budget_array['Hourly'] = $list1;
                $budget_array['Fixed'] = $list2;
                $data ['detail'] = $budget_array;
            } else {
                $data ['error'] = 'Nothing Found';
            }
        } else {
            $data ['error'] = 'No Budget Found';
        }

        return $this->sendResponse ( $data );
    }

    public function actionGet($id) {
        $data = [ ];

        $model = Budget::find ()->where ([
            'id'=>$id
        ])->one();
        if (! empty ( $model )) {
            $data ['status'] = self::API_OK;
            $data ['detail'] = $model->asJson();
        } else {
            $data ['error'] = 'No Budget Found';
        }

        return $this->sendResponse ( $data );
    }

}
