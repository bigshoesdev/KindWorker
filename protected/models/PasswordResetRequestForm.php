<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {
	public $email;
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						'email',
						'filter',
						'filter' => 'trim' 
				],
				[ 
						'email',
						'required' 
				],
				[ 
						'email',
						'email' 
				],
				[ 
						'email',
						'exist',
						'targetClass' => '\app\models\User',
						'filter' => [ 
								'state_id' => User::STATE_ACTIVE 
						],
						'message' => 'There is no user with such email.' 
				] 
		];
	}
	
	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail() {
		/* @var $user User */
		$user = User::findOne ( [ 
				'state_id' => User::STATE_ACTIVE,
				'email' => $this->email 
		] );
		
		if (! $user) {
			return false;
		}
		
		if (! User::isPasswordResetTokenValid ( $user->activation_key )) {
			$user->generatePasswordResetToken ();
		}
		
		if (! $user->save ()) {
			return false;
		}
		
		return Yii::$app->mailer->compose ( [ 
				'html' => 'passwordResetToken' 
		], [ 
				'user' => $user 
		] )->setFrom ( [ 
				\Yii::$app->params ['adminEmail'] => \Yii::$app->name . ' robot' 
		] )->setTo ( $this->email )->setSubject ( 'Password reset for ' . \Yii::$app->name )->send ();
	}
}
