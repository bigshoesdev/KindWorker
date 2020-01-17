<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\components\TRichTextEditor;
use app\models\Job;
use app\models\Transaction;
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
 * PayController implements the API actions for Category model.
 */
class PayController extends ApiTxController {
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
                            'add-charge',
                            'release-pay'
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
    public function actionAddCharge()
    {
		$data = [ ];
		\Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
        $user = \yii::$app->user->identity;
        $requestData = Yii::$app->request->post ();
		$transaction = new Transaction();
		$flag = false;
        if ($transaction->load($requestData)) {
            try {
                $stripe_charge = \Stripe\Charge::create ( array (
                        "amount" => $transaction->amount * 100,
                        "description" => "Payment Charge",
                        "source" => $requestData ['Pay'] ['token'],
                        "currency" => "USD"
                ) );

                if ($stripe_charge->status == "succeeded") {
                    $transaction->reciever_id = $requestData ['Transaction']['receiver_id'];
                    $transaction->charge_id = $stripe_charge->id;
                    $transaction->transaction_num = $stripe_charge->balance_transaction;
                    $transaction->amount = $transaction->amount;
                    $transaction->currency = 'USD';
                    $transaction->state_id = Transaction::STATE_CHARGE;
                    $transaction->creeated_by_id = $user->id;
                    if ($transaction->save()) {
                        $data ['status'] = self::API_OK;
                        $data ['message'] = 'Payment is charged';
                        $flag = true;
                    } else {
                        $data ['error'] = $transaction->getErrorsString();
                    }
                }
            } catch ( \Exception $e ) {
                $data ['error'] = $e->getMessage ();
            }
            if($flag == false && $requestData['Transaction']['model_type'] == 'Job'){
                $job_model = Job::find()->where([
                    'id'=>$requestData['Transaction']['model_id']
                ])->one();
                if(!empty($job_model)){
                    $job_model->delete();
                }
            }
        } else {
            $data ['error'] = 'No Data posted';
        }

		return $this->sendResponse ( $data );
    }

    public function actionReleasePay()
    {
        $data = [ ];
        \Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
        $user = \yii::$app->user->identity;
        $requestData = Yii::$app->request->post ();
        if(!empty($requestData)){
            $transaction = Transaction::find()->where([
                'model_type' => $requestData['Pay']['model_type'],
                'model_id' => $requestData['Pay']['model_id'],
                'reciever_id' => $requestData['Pay']['receiver_id'],
                'created_by_id' => $user->id
            ])->one();
            if (!empty($transaction)) {
                try {
                    $stripe_charge = \Stripe\Transfer::create ( array (
                        "amount" => $transaction->amount * 100,
                        "currency" => "USD",
                        "description" => "Payment Released",
                        "source_transaction" => $transaction->charge_id,
                        "destination" => $transaction->reciever->stripe_customer_id
                    ) );
                    if ($stripe_charge->status == "succeeded") {
                        $transaction->state_id = Transaction::STATE_SUCCESS;
                        if ($transaction->save()) {
                            $data ['status'] = self::API_OK;
                            $data ['message'] = 'Payment is successed';
                        } else {
                            $data ['error'] = $transaction->getErrorsString();
                        }
                    }
                } catch ( \Exception $e ) {
                    $data ['error'] = $e->getMessage ();
                }
            } else {
                $data ['error'] = 'Transaction is not found.';
            }
        } else{
            $data ['error'] = 'No data posted';
        }

        return $this->sendResponse ( $data );
    }

}
