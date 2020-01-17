<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_auth_session".
 *
 * @property integer $id
 * @property string $auth_code
 * @property string $device_token
 * @property integer $type_id
 * @property integer $created_by_id
 * @property string $create_time
 * @property string $update_time === Related data ===
 * @property User $createUser
 */
namespace app\models;

use app\models\User;
use Yii;
use yii\components;
use yii\web\HttpException;

class AuthSession extends \app\components\TActiveRecord {
    const TYPE_ANDROID = 1;
    const TYPE_IPHONE = 0;

    public function __toString() {
        return ( string ) $this->auth_code;
    }

    public static function getTypeOptions($id = null) {
        $list = array (
            self::TYPE_ANDROID => "Android",
            self::TYPE_IPHONE => "IPhone"
        );
        if ($id === null)
            return $list;
    }

	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->created_by_id ))
				$this->created_by_id = Yii::$app->user->id;
			if (! isset ( $this->created_on ))
				$this->created_on = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->updated_on ))
				$this->updated_on = date ( 'Y-m-d H:i:s' );
		} else {
			$this->updated_on = date ( 'Y-m-d H:i:s' );
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%auth_session}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'auth_code',
								'device_token',
								'created_by_id',
								'created_on',
								'updated_on' 
						],
						'required' 
				],
				[ 
						[ 
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on',
								'updated_on' 
						],
						'safe' 
				],
				[ 
						[ 
								'auth_code',
								'device_token' 
						],
						'string',
						'max' => 256 
				],
				[ 
						[ 
								'created_by_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => User::className (),
						'targetAttribute' => [ 
								'created_by_id' => 'id' 
						] 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'auth_code' => Yii::t ( 'app', 'Auth Code' ),
				'device_token' => Yii::t ( 'app', 'Device Token' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'created_by_id' => Yii::t ( 'app', 'Create User' ),
				'create_time' => Yii::t ( 'app', 'Create Time' ),
				'update_time' => Yii::t ( 'app', 'Update Time' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreateUser() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'created_by_id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['created_by_id'] = [ 
				'createUser',
				'User',
				'id' 
		];
		return $relations;
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['auth_code'] = $this->auth_code;
		$json ['device_token'] = $this->device_token;
		$json ['type_id'] = $this->type_id;
		$json ['created_by_id'] = $this->created_by_id;
		$json ['created_on'] = $this->created_on;
		if ($with_relations) {
			// CreateUser
			$list = $this->getCreateUser ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreateUser'] = $relationData;
			} else {
				$json ['CreateUser'] = $list;
			}
		}
		return $json;
	}
	private static $session_expiration_days = 0;
	public function beforeSave($insert) {
		return parent::beforeSave ( $insert );
	}
	
	public static function randomCode($count) {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array (); // remember to declare $pass as an array
		$alphaLength = strlen ( $alphabet ) - 1; // put the length -1 in cache
		for($i = 0; $i < $count; $i ++) {
			$n = rand ( 0, $alphaLength );
			$pass [] = $alphabet [$n];
		}
		$p = implode ( $pass );
		return implode ( $pass );
	}
	public static function newSession($model) {
		self::deleteOldSession ( Yii::$app->user->identity->id );

		$auth_session = new AuthSession ();
		$auth_session->created_by_id = Yii::$app->user->identity->id;
        $auth_session->auth_code = self::randomCode ( $count = 32 );
        $auth_session->device_token = $model->device_token;
        $auth_session->type_id = $model->device_type;
        if ($auth_session->save ()) {
			return $auth_session;
		}
		throw new HttpException ( 500, Yii::t ( 'app', 'auth token not generated' ) );
	}
	public static function deleteOldSession($id) {
		$old = AuthSession::findAll ( [ 
				'created_by_id' => $id 
		] );
		foreach ( $old as $session ) {
			$session->delete ();
		}
		
		return true;
	}
	public static function getHead() {
		if (! function_exists ( 'getallheaders' )) {
			function getallheaders() {
				$headers = '';
				foreach ( $_SERVER as $name => $value ) {
					if (substr ( $name, 0, 5 ) == 'HTTP_') {
						$headers [str_replace ( ' ', '-', ucwords ( strtolower ( str_replace ( '_', ' ', substr ( $name, 5 ) ) ) ) )] = $value;
					}
				}
				return $headers;
			}
		}
		return getallheaders ();
	}
/* 	public static function authenticateSession($auth_code = null) {
		if (! Yii::$app->user->isGuest)
			return true;
		if ($auth_code == null) {
			if ($auth_code == null) {
				
				$auth_code = isset ( \Yii::$app->request->headers ['auth_code'] ) ? \Yii::$app->request->headers ['auth_code'] : Yii::$app->request->getQueryParam ( 'auth_code' );
			}
			if ($auth_code == null)
				return false;
		}
		
		$auth_session = AuthSession::findOne ( array (
				'auth_code' => $auth_code 
		) );
		
		if ($auth_session != null) {
			
			if ($auth_session->createUser != null) {
				$user = $auth_session->createUser;
				Yii::$app->user->login ( $user, 3600 * 24 * 90 );
				$auth_session->save (); // update time is changed here
				return true;
			} else {
				throw new ForbiddenHttpException ( "auth code required." );
			}
		}
		return false;
	} */
	public static function authenticateSession($auth_code = null) {
		if (! Yii::$app->user->isGuest)
			return true;
			if ($auth_code == null) {
				if ($auth_code == null) {
					
					$auth_code = isset ( \Yii::$app->request->headers ['auth_code'] ) ? \Yii::$app->request->headers ['auth_code'] : Yii::$app->request->getQueryParam ( 'auth_code' );
				}
				if ($auth_code == null)
					return false;
			}
			
			$auth_session = AuthSession::findOne ( array (
					'auth_code' => $auth_code
			) );
			
			if ($auth_session != null) {
				
				if ($auth_session->createUser != null) {
					$user = $auth_session->createUser;
					Yii::$app->user->login ( $user, 3600 * 24 * 90 );
					$auth_session->save (); // update time is changed here
					return true;
				} else {
					throw new ForbiddenHttpException ( "auth code required." );
				}
			} else {
				\Yii::$app->user->logout ();
				throw new HttpException ( 403, Yii::t ( 'app', 'auth code required' ) ); 		}
				return false;
	}
}