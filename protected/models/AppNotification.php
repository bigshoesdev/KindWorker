<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_app_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $message
 * @property integer $state_id
 * @property integer $type_id
 * @property string $create_time
 * @property integer $create_user_id === Related data ===
 * @property User $createUser
 * @property User $user
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class AppNotification extends \app\components\TActiveRecord {
	public $customer;
	public $worker;
	public $customer_message;
	public $worker_message;
	public function __toString() {
		return ( string ) $this->user_id;
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
	const TYPE_PENDING = 0;
	const TYPE_SENT = 1;
	public static function getTypeOptions() {
		return [
				self::TYPE_PENDING => "Pending",
				self::TYPE_SENT => "Sent",
		];
	}
	public function getType() {
		$list = self::getTypeOptions ();
		return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
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

	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->user_id ))
				$this->user_id = Yii::$app->user->id;
			if (! isset ( $this->create_time ))
				$this->create_time = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->create_user_id ))
				$this->create_user_id = Yii::$app->user->id;
		} else {
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%app_notification}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
			
				[ 
						[ 
								'user_id',
								'state_id',
								'type_id',
								'create_user_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'create_time' ,
								'customer',
								'worker',
								'customer_message',
								'worker_message'
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
								'create_user_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => User::className (),
						'targetAttribute' => [ 
								'create_user_id' => 'id' 
						] 
				],
				[ 
						[ 
								'user_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => User::className (),
						'targetAttribute' => [ 
								'user_id' => 'id' 
						] 
				],
				[ 
						[ 
								'message' 
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
				'user_id' => Yii::t ( 'app', 'User' ),
				'message' => Yii::t ( 'app', 'Message' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'create_time' => Yii::t ( 'app', 'Create Time' ),
				'create_user_id' => Yii::t ( 'app', 'Create User' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreateUser() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'create_user_id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'user_id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['create_user_id'] = [ 
				'createUser',
				'User',
				'id' 
		];
		$relations ['user_id'] = [ 
				'user',
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
		$json ['user_id'] = $this->user_id;
		$json ['message'] = $this->message;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['create_time'] = $this->create_time;
		$json ['create_user_id'] = $this->create_user_id;
		if ($with_relations) {
			// CreateUser $list = $this->getCreateUser()->all();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreateUser'] = $relationData;
			} else {
				$json ['CreateUser'] = $list;
			}
			// User $list = $this->getUser()->all();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['User'] = $relationData;
			} else {
				$json ['User'] = $list;
				}
			}
		return $json;
	}

}
