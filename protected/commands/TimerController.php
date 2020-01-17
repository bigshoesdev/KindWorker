<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\commands;

use app\models\EmailQueue;
use yii\console\Controller;
use app\models\AppNotification;
use app\models\AuthSession;
use app\models\Notification;

class TimerController extends Controller {
	const MAX_ATTEMPTS = 5;
	public function actionEmail() {
		$mails = EmailQueue::find ()->where ( [ 
				'state_id' => EmailQueue::STATUS_PENDING 
		] )->andWhere ( [ 
				'<=',
				'attempts',
				self::MAX_ATTEMPTS 
		] )->limit ( 50 )->orderBy ( 'id asc' )->all ();
		
		foreach ( $mails as $mail ) {
			$mail->sendNow ( $mail->to_email, $mail->message, $mail->from_email, $mail->subject );
		}
		return true;
	}
	public function actionAppNoty() {
		$auth = AuthSession::find ()->select ( 'create_user_id' );
		$query = AppNotification::find ()->andFilterWhere ( [ 
				'IN',
				'user_id',
				$auth 
		] );
		
		if (! empty ( $query )) {
			foreach ( $query->batch ( 10 ) as $models ) {
				foreach ( $models as $model ) {
					$model->type_id = AppNotification::TYPE_SENT;
					if ($model->save ()) {
						Notification::newAppNotify ( $model->user_id, $model->message );
					}
				}
			}
			return true;
		}
		return false;
	}
	public function actionAppNotyDel() {
		$query = AppNotification::find ()->where ( [ 
				'type_id' => AppNotification::TYPE_SENT 
		] );
		
		if (! empty ( $query )) {
			foreach ( $query->batch ( 10 ) as $models ) {
				foreach ( $models as $model ) {
					$model->delete();
				}
			}
			return true;
		}
		return false;
	}
}

