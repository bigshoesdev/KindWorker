<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_shadow".
 *
 * @property integer $id
 * @property integer $to_id
 * @property integer $state_id
 * @property string $create_time
 * @property integer $create_user_id
 *
 */
namespace app\models;

use Yii;
use yii\components;

class Shadow extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->to_id;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	public static function getStateOptions() {
		return [ 
				self::STATE_INACTIVE => "In-Active",
				self::STATE_ACTIVE => "Active",
				self::STATE_DELETED => "Delete" 
		
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
	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->create_time ))
				$this->created_on = date ( 'Y-m-d H:i:s' );
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
		return '{{%shadow}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'to_id' 
						],
						'required' 
				],
				[ 
						[ 
								'to_id',
								'state_id',
								'create_user_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on' 
						],
						'safe' 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'to_id' => Yii::t ( 'app', 'To' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'created_on' => Yii::t ( 'app', 'Create Time' ),
				'create_user_id' => Yii::t ( 'app', 'Create User' ) 
		];
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		return $relations;
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['to_id'] = $this->to_id;
		$json ['state_id'] = $this->state_id;
		$json ['created_on'] = $this->created_on;
		$json ['create_user_id'] = $this->create_user_id;
		if ($with_relations) {
		}
		return $json;
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreateUser()
	{
		return $this->hasOne(User::className(), ['id' => 'create_user_id']);
	}
}