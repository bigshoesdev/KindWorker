<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_notification".
 *
 * @property integer $id
 * @property string $message
 * @property integer $model_id
 * @property string $model_type
 * @property integer $is_read
 * @property integer $state_id
 * @property integer $type_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;
use app\components\FirebaseNotifications;

class Notification extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->message;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	public static function getStateOptions() {
		return [ 
				self::STATE_INACTIVE => "New",
				self::STATE_ACTIVE => "Active",
				self::STATE_DELETED => "Archieved" 
		];
	}
	public function getState() {
		$list = self::getStateOptions ();
		return isset ( $list [$this->state_id] ) ? $list [$this->state_id] : 'Not Defined';
	}
	public function getStateBadge() {
		$list = [ 
				self::STATE_INACTIVE => "primary",
				self::STATE_ACTIVE => "success",
				self::STATE_DELETED => "danger" 
		];
		return isset ( $list [$this->state_id] ) ? \yii\helpers\Html::tag ( 'span', $this->getState (), [ 
				'class' => 'label label-' . $list [$this->state_id] 
		] ) : 'Not Defined';
	}
	public static function getTypeOptions() {
		return [ 
				"TYPE1",
				"TYPE2",
				"TYPE3" 
		];
	}
	public function getType() {
		$list = self::getTypeOptions ();
		return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
	}
	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->create_time ))
				$this->create_time = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->update_time ))
				$this->update_time = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->created_by_id ))
				$this->created_by_id = Yii::$app->user->id;
		} else {
			$this->update_time = date ( 'Y-m-d H:i:s' );
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%notification}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'model_id',
								'model_type',
								'create_time',
								'update_time',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'model_id',
								'is_read',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'create_time',
								'update_time' 
						],
						'safe' 
				],
				[ 
						[ 
								'message' 
						],
						'string',
						'max' => 255 
				],
				[ 
						[ 
								'model_type' 
						],
						'string',
						'max' => 125 
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
				],
				[ 
						[ 
								'message',
								'model_type' 
						],
						'trim' 
				],
				[ 
						[ 
								'state_id' 
						],
						'in',
						'range' => array_keys ( self::getStateOptions () ) 
				],
				[ 
						[ 
								'type_id' 
						],
						'in',
						'range' => array_keys ( self::getTypeOptions () ) 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'message' => Yii::t ( 'app', 'Message' ),
				'model_id' => Yii::t ( 'app', 'Model' ),
				'model_type' => Yii::t ( 'app', 'Model Type' ),
				'is_read' => Yii::t ( 'app', 'Is Read' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'create_time' => Yii::t ( 'app', 'Create Time' ),
				'update_time' => Yii::t ( 'app', 'Update Time' ),
				'created_by_id' => Yii::t ( 'app', 'Created By' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'created_by_id' 
		] );
	}
	public function getModelUser() {
		return $this->hasOne ( User::className (), [
				'id' => 'model_id'
		] );
	}
	public function getJob() {
		return $this->hasOne ( Job::className (), [
				'id' => 'job_id'
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['created_by_id'] = [ 
				'createdBy',
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
		$json ['message'] = $this->message;
		$json ['model_id'] = $this->model_id;
		$json ['model_type'] = $this->model_type;
		$json ['is_read'] = $this->is_read;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['create_time'] = $this->create_time;
		$json ['created_by_id'] = $this->created_by_id;
		if ($with_relations) {
			// CreatedBy $list = $this->getCreatedBy()->all();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreatedBy'] = $relationData;
			} else {
				$json ['CreatedBy'] = $list;
			}
		}
		return $json;
	}
	public static function notification($model, $message, $job_id = null,$id) {
		$notification = new Notification();
		$notification->message = $message;
		$notification->model_id = $id;
		$notification->model_type = get_class ( $model );
		if ($notification->save ()) {
			Notification::newAppNotify ( $job_id, $id, $message );
		}
	}

	public static function newAppNotify($job_id = null, $id, $message) {
		$notification = new FirebaseNotifications();
		$androidtoken = [ ];
		$iostoken = [ ];
		$tokens = "";
		$data = [ ];
		$data ['controller'] = \yii::$app->controller->id;
		$data ['action'] = \yii::$app->controller->action->id;
		$data ['job_id'] = $job_id;
		$data ['user_id'] = $id;
		$data ['message'] = $message;
		
		$user = User::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		
		if (! empty ( $user )) {
			$data ['role_id'] = $user->role_id;
			$tokens = $user->authSessions;
			if (count ( $tokens ) > 0) {
				foreach ( $tokens as $token ) {
					if ($token->type_id == 0) {
						$androidtoken [] = $token->device_token;
					}
					if ($token->type_id == 1)
						$iostoken [] = $token->device_token;
				}
				\yii::error ( $data );
				if (! empty ( $androidtoken )) {
					$notification->sendGcmDataMessage ( $androidtoken, $data );
				}
				if (! empty ( $iostoken )) {
//					$apns = \Yii::$app->apns;
//					foreach ( $iostoken as $tokn ) {
//						$out = $apns->send ( $tokn, $data ['message'], $data, [
//								'sound' => 'default',
//								'badge' => 1
//						] );
//						\yii::error ( $out );
//					}
                    $notification->sendApnsDataMessage ( $iostoken, $data );
				}
			}
		} else {
			$data ['message'] = 'User Not Found';
		}
	}

}
