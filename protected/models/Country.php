<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $id
 * @property string $sortname
 * @property string $name
 * @property integer $phonecode
 * @property integer $type_id
 * @property integer $state_id
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 * @property State[] $states
 */
namespace app\models;

use app\models\State;
use app\models\User;
use Yii;
use yii\components;

class Country extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->name;
	}
	const TYPE_NOT_APPROVED = 0;
	const TYPE_APPROVED = 1;
	
	public static function getTypeOptions() {
		return [ 
				self::TYPE_NOT_APPROVED=> "Not Approved City",
				self::TYPE_APPROVED=> "Approved City",
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
		return '{{%country}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'sortname',
								'name',
								'phonecode',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'phonecode',
								'type_id',
								'state_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'sortname' 
						],
						'string',
						'max' => 3 
				],
				[ 
						[ 
								'name' 
						],
						'string',
						'max' => 150 
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
								'sortname',
								'name' 
						],
						'trim' 
				],
				[ 
						[ 
								'sortname' 
						],
						'app\components\TNameValidator' 
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
				'sortname' => Yii::t ( 'app', 'Sortname' ),
				'name' => Yii::t ( 'app', 'Name' ),
				'phonecode' => Yii::t ( 'app', 'Phonecode' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'state_id' => Yii::t ( 'app', 'State' ),
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
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getStates() {
		return $this->hasMany ( State::className (), [ 
				'country_id' => 'id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		$relations ['country_id'] = [ 
				'states',
				'State',
				'id' 
		];
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
		// State::deleteRelatedAll(['id'=>$this->country_id]);
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['sortname'] = $this->sortname;
		$json ['name'] = $this->name;
		$json ['phonecode'] = $this->phonecode;
		$json ['type_id'] = $this->type_id;
		$json ['state_id'] = $this->state_id;
		$json ['created_by_id'] = $this->created_by_id;
		if ($with_relations) {
			// CreatedBy
			$list = $this->getCreatedBy ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreatedBy'] = $relationData;
			} else {
				$json ['CreatedBy'] = $list;
			}
			// States
			$list = $this->getStates ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['States'] = $relationData;
			} else {
				$json ['States'] = $list;
			}
		}
		return $json;
	}
}
