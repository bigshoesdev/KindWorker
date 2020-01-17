<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_log".
 *
 * @property integer $id
 * @property string $error
 * @property string $api
 * @property string $description
 * @property integer $state_id
 * @property string $link
 * @property integer $type_id
 * @property string $created_on
 *
 */
namespace app\models;

use Yii;
use yii\components;

class Log extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->error;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	const TYPE_WEB = 0;
	const TYPE_API = 1;
	public static function getStateOptions() {
		return [ 
				self::STATE_INACTIVE => "New",
				self::STATE_ACTIVE => "Active",
				self::STATE_DELETED => "Archived" 
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
			if (! isset ( $this->created_on ))
				$this->created_on = date ( 'Y-m-d H:i:s' );
		} else {
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%log}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'error',
								'link',
								'type_id',
								'created_on' 
						],
						'required' 
				],
				[ 
						[ 
								'api',
								'description' 
						],
						'string' 
				],
				[ 
						[ 
								'state_id',
								'type_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on' 
						],
						'safe' 
				],
				[ 
						[ 
								'error' 
						],
						'string',
						'max' => 256 
				],
				[ 
						[ 
								'link' 
						],
						'string',
						'max' => 255 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'error' => Yii::t ( 'app', 'Error' ),
				'api' => Yii::t ( 'app', 'Api' ),
				'description' => Yii::t ( 'app', 'Description' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'link' => Yii::t ( 'app', 'Link' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'created_on' => Yii::t ( 'app', 'Created On' ) 
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
		$json ['error'] = $this->error;
		$json ['api'] = $this->api;
		$json ['description'] = $this->description;
		$json ['state_id'] = $this->state_id;
		$json ['link'] = $this->link;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		if ($with_relations) {
		}
		return $json;
	}
}