<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\User;
use yii;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use bryglen\braintree\Braintree;
use yii\data\ActiveDataProvider;
use app\modules\api\controllers\ApiTxController;
use app\models\AvailabilitySlot;

/**
 * BraintreeController implements the API actions for Category model.
 */
class BraintreeController extends ApiTxController {
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
                            'get-client-token',
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'index'
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
     * Lists all Payment models.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->txindex ( "app\models\Payment" );
    }

    public function actionGetClientToken(){
        $data = [];
        $user = User::find()->where([
            'id' => \yii::$app->user->id
        ])->one();
        $braintree = Yii::$app->braintree;
        $clientToken = $braintree->call('ClientToken', 'generate', [
            'customerId' => $user->braintree_id
        ]);
        if(!empty($clientToken)){
            $data['status'] = self::API_OK;
            $data ['client_Token'] = $clientToken;
        }else{
            $data['error'] = 'ClientToken cannot create.';
        }
        return $this->sendResponse ( $data );
    }

//    public function actionCreatePaymentMethod()
//    {
//        $data = [];
//        $user = User::find()->where([
//            'id' => \yii::$app->user->id
//            ])->one();
//        if(empty($user)){
//            $data['error'] = 'Autherized user is not';
//            return $this->sendResponse ( $data );
//        }
//        $params = Yii::$app->request->post ();
//        if(isset($params) && !empty($params['payment_method_nonce'])) {
//            $braintree = Yii::$app->braintree;
//            $result = $braintree->call('PaymentMethod', 'create', [
//                'customerId' => $user->braintree_id,
//                'paymentMethodNonce' => $params['payment_method_nonce'],
//                'options' => [
//                    'failOnDuplicatePaymentMethod' => true
//                ]
//            ]);
//            $paymentmethod = Payment::find()->where([
//                'paymentmethod_token' => $result->token,
//                'customer_id' => $user->braintree_id
//            ])->one();
//            if (empty($paymentmethod)) {
//                $paymentmethod = new Payment();
//                $paymentmethod->customer_id = $user->braintree_id;
//                $paymentmethod->paymentmethod_token = $params['payment_method_nonce'];
//                $paymentmethod->created_by_id = $user->id;
//                if($paymentmethod->save()){
//                    $data['status'] = self::API_OK;
//                    $data['result'] = 'New PaymentMethod is saved';
//                }else{
//                    $data['error'] = 'PaymentMethod save is fail';
//                }
//            }else{
//                $data['status'] = self::API_OK;
//                $data['result'] = 'PaymentMethod is existed';
//            }
//        } else{
//            $data['error'] = 'Autherized user is not';
//        }
//        return $this->sendResponse ( $data );
//    }

}
