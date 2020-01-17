<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Message;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\modules\api\controllers\ApiTxController;

/**
 * MessageController implements the API actions for Message model.
 */
class MessageController extends ApiTxController {
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
												'send-message',
												'get-chat',
												'get-buyer-list',
												'get-message',
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
	 * Lists all Message models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txindex ( "app\models\Message" );
	}
	
		public function actionSendMessage($to_id) {
		$response = [];
		$model = new \app\models\Message ();
		$from_id = \Yii::$app->user->identity->id;
				
			$model->to_id = $to_id;
			$model->from_id = $from_id;
				
			if ($model->load ( \Yii::$app->request->post () )) {
	
				if (isset ( $_POST ['Message'] ['send_time'] )) {
					$model->send_time = $_POST ['Message'] ['send_time'];
				} else {
					$model->send_time = "";
				}
				if (isset ( $_POST ['Message'] ['message'] )) {
					$model->message = $_POST ['Message'] ['message'];
				} else {
					$model->message = "";
				}
				$model->session_id = $to_id > $from_id ? $from_id . '-' . $to_id : $to_id . '-' . $from_id;
				$model->state_id = Message::STATUS_UNREAD;
				if ($model->save ()) {
					$response ['details'] = $model->asJson ();
					$response ['status'] = 'OK';
					$content = \Yii::$app->user->identity->full_name . ' has sent you a message';
				//	Post::sendNotification($to_id, $content);
				} else {
					$response ['error'] = $model->getErrors();
				}
			}
			return $this->sendResponse($data);
	}
	public function actionSendAkn() {
		$response = $this->getResponse ();
		$ids = [ ];
		if (isset ( $_POST ['Message'] ['message_id'] )) {
			$ids = explode ( ',', $_POST ['Message'] ['message_id'] );
			if ($ids != null) {
				foreach ( $ids as $id ) {
					if ($id) {
						$msg = Message::findOne ( $id );
						if ($msg) {
							$msg->state_id = Message::STATUS_READ;
							$msg->save ();
						}
					}
				}
				$response ['status'] = 'OK';
			} else {
				$response ['status'] = Yii::t ( 'app', 'Ids are null' );
			}
		}
		return $response;
	}
	public function actionGetChat($id) {
		$response = $this->getResponse ();
		$uid = \Yii::$app->user->id;
		$dataProvider = new ActiveDataProvider ( [
				'query' => \app\models\Message::find ()->where ( [
	
						'created_by_id' => $id
				] )->orderBy ( 'id desc' ),
				'pagination' => [
						'pageSize' => 30
				]
		] );
		$Json = [ ];
		if ($dataProvider->models) {
			foreach ( $dataProvider->getModels () as $data ) {
				$Json [] = $data->asJson ();
			}
			$response ['details'] = $Json;
			$response ['status'] = 'OK';
		} else {
			$response ['message'] = Yii::t ( 'app', 'Chat not found' );
		}
		return $response;
	}
	public function actionGetMessage() {
		$response = $this->getResponse ();
		$id = \Yii::$app->user->id;
		$results = Message::findAll ( [
				'to_id' => $id,
				'state_id' => Message::STATUS_UNREAD
		] );
		if ($results != null) {
			$list = [ ];
			foreach ( $results as $msg ) {
				$msg->state_id = Message::STATUS_READ;
				if ($msg->save ()) {
					$list [] = $msg->asJson ();
				}
			}
			$response ['status'] = 'OK';
			$response ['details'] = $list;
		} else {
			$response ['error'] = 'No unread message found';
		}
		return $response;
	}
   
}
