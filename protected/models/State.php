<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_state".
 *
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property integer $type_id
 * @property integer $state_id
 * @property integer $created_by_id === Related data ===
 * @property Country $country
 * @property User $createdBy
 */
namespace app\models;

use app\models\Country;
use app\models\User;
use Yii;
use yii\components;

class State extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->name;
	}
	const TYPE_NOT_APPROVED = 0;
	const TYPE_APPROVED = 1;
	public static function getTypeOptions() {
		return [ 
				self::TYPE_NOT_APPROVED=> "Not Approved State",
				self::TYPE_APPROVED=> "Approved State",
		];
	}
	public function getType() {
		$list = self::getTypeOptions ();
		return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
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
	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->created_by_id ))
				$this->created_by_id = Yii::$app->user->id;
		} else {
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%state}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'name',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'country_id',
								'type_id',
								'state_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'name' 
						],
						'string',
						'max' => 30 
				],
				[ 
						[ 
								'country_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => Country::className (),
						'targetAttribute' => [ 
								'country_id' => 'id' 
						] 
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
								'name' 
						],
						'trim' 
				],
				[ 
						[ 
								'name' 
						],
						'app\components\TNameValidator' 
				],
				[ 
						[ 
								'type_id' 
						],
						'in',
						'range' => array_keys ( self::getTypeOptions () ) 
				],
				[ 
						[ 
								'state_id' 
						],
						'in',
						'range' => array_keys ( self::getStateOptions () ) 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'name' => Yii::t ( 'app', 'Name' ),
				'country_id' => Yii::t ( 'app', 'Country' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'created_by_id' => Yii::t ( 'app', 'Created By' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCountry() {
		return $this->hasOne ( Country::className (), [ 
				'id' => 'country_id' 
		] );
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
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['country_id'] = [ 
				'country',
				'Country',
				'id' 
		];
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
		$json ['name'] = $this->name;
		$json ['country_id'] = $this->country_id;
		$json ['type_id'] = $this->type_id;
		$json ['state_id'] = $this->state_id;
		$json ['created_by_id'] = $this->created_by_id;
		if ($with_relations) {
			// Country
			$list = $this->getCountry ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['Country'] = $relationData;
			} else {
				$json ['Country'] = $list;
			}
			// CreatedBy
			$list = $this->getCreatedBy ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreatedBy'] = $relationData;
			} else {
				$json['CreatedBy'] 	= $list;
				}	
			}
		return $json;
	}

}
