<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {
	public $username;
	public $password;
	public $rememberMe = false;
	private $_user = false;
	public $device_token;
	public $device_type;
	public function asJson() {
		$Json = [ ];
		$Json ['username'] = isset($this->username)?$this->username:'';
		$Json ['device_token'] = isset($this->device_token)?$this->device_token:'';
		$Json ['device_type'] = isset($this->device_type)?$this->device_type:0;
		return $Json;
	}
	
	/**
	 *
	 * @return array the validation rules.
	 */
	public function rules() {
		return [ 
				// username and password are both required
				[ 
						[ 
								'username',
								'password' 
						],
						'required' 
				],
				
				[ 
						[ 
								'username' 
						],
						'email',
						'message' => 'Email is not a valid email address.' 
				],
				// rememberMe must be a boolean value
				[ 
						'rememberMe',
						'boolean' 
				],
				[ 
						[ 
								'device_token',
								'device_type' 
						],
						'safe' 
				],
				// password is validated by validatePassword()
				[ 
						'password',
						'validatePassword' 
				] 
		];
	}
	public function attributeLabels() {
		return [
				'username' => Yii::t ( 'app', 'Email Id' ),
				'password' => Yii::t ( 'app', 'Password' ),
				'rememberMe' => Yii::t ( 'app', 'Remember Me' ),
				'device_token' => Yii::t ( 'app', 'Device Token' ),
				'device_type' => Yii::t ( 'app', 'Device Type' )
		];
	}
	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute
	 *        	the attribute currently being validated
	 * @param array $params
	 *        	the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params) {
		if (! $this->hasErrors ()) {
			$user = $this->getUser ();
			
			if (! $user || ! $user->validatePassword ( $this->password )) {
				
				$this->addError ( $attribute, 'Incorrect email-Id or password.' );
			}
		}
	}
	
	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login() {
		if ($this->validate ()) {
			LoginHistory::add ( true, $this->getUser (), null );
			return Yii::$app->user->login ( $this->getUser (), $this->rememberMe ? 3600 * 24 * 30 : 0 );
		}
		LoginHistory::add ( false, $this->getUser (), $this->errors);
		return false;
	}
	
	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser() {
		if ($this->_user === false) {
			$this->_user = User::findByUsername ( $this->username );
		}
		
		return $this->_user;
	}
}
