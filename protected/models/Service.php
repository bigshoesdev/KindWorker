<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_service".
 *
 * @property integer $id
 * @property integer $service_type
 * @property integer $category_id
 * @property integer $sub_category_id
 * @property string $description
 * @property integer $rate_type
 * @property string $price
 * @property integer $availablity_slot_id
 * @property string $zipcode
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property Category $category
 * @property User $createdBy
 * @property SubCategory $subCategory
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\Category;
use app\models\User;
use app\models\SubCategory;

class Service extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->service_type;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	
	const FIXED = 1;
	const HOURLY = 0;
	
	const LOCAL = 0;
	const REMOTE = 1;
	
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
			if (! isset ( $this->updated_on ))
				$this->updated_on = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->created_by_id ))
				$this->created_by_id = Yii::$app->user->id;
		} else {
			$this->updated_on = date ( 'Y-m-d H:i:s' );
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%service}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'service_type',
								'category_id',
								'sub_category_id',
								'description',
								'rate_type',
								'price',
							//	'zipcode',
								'created_on',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'service_type',
								'category_id',
								'sub_category_id',
								'rate_type',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on',
								'updated_on' ,
								'delivery_day'
						],
						'safe' 
				],
				[ 
						[ 
								'description' 
						],
						'string',
						'max' => 255 
				],
				[ 
						[ 
								'price',
								'zipcode' 
						],
						'string',
						'max' => 256 
				],
				[ 
						[ 
								'category_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => Category::className (),
						'targetAttribute' => [ 
								'category_id' => 'id' 
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
								'sub_category_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => SubCategory::className (),
						'targetAttribute' => [ 
								'sub_category_id' => 'id' 
						] 
				],
				[ 
						[ 
								'description',
								'price',
								'zipcode' 
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
				'service_type' => Yii::t ( 'app', 'Service Type' ),
				'category_id' => Yii::t ( 'app', 'Category' ),
				'sub_category_id' => Yii::t ( 'app', 'Sub Category' ),
				'description' => Yii::t ( 'app', 'Description' ),
				'rate_type' => Yii::t ( 'app', 'Rate Type' ),
				'price' => Yii::t ( 'app', 'Price' ),
				'zipcode' => Yii::t ( 'app', 'Zipcode' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'created_on' => Yii::t ( 'app', 'Created On' ),
				'updated_on' => Yii::t ( 'app', 'Updated On' ),
				'created_by_id' => Yii::t ( 'app', 'Created By' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory() {
		return $this->hasOne ( Category::className (), [ 
				'id' => 'category_id' 
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
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSubCategory() {
		return $this->hasOne ( SubCategory::className (), [ 
				'id' => 'sub_category_id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['category_id'] = [ 
				'category',
				'Category',
				'id' 
		];
		$relations ['created_by_id'] = [ 
				'createdBy',
				'User',
				'id' 
		];
		$relations ['sub_category_id'] = [ 
				'subCategory',
				'SubCategory',
				'id' 
		];
		return $relations;
	}
	public function beforeDelete() {
		
		AvailabilitySlot::deleteRelatedAll ( [
				'service_id' => $this->id
		] );
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['service_type'] = $this->service_type;
		$json ['category_id'] = $this->category_id;
		$json ['sub_category_id'] = $this->sub_category_id;
		$json ['description'] = $this->description;
		$json ['rate_type'] = $this->rate_type;
		$json ['price'] = $this->price;
		$json ['delivery_days'] = $this->delivery_day;
		$json ['zipcode'] = $this->zipcode;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		if ($with_relations) {
			// Category
			$list = $this->getCategory ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['Category'] = $relationData;
			} else {
				$json ['Category'] = $list;
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
				$json ['CreatedBy'] = $list;
			}
			// SubCategory
			$list = $this->getSubCategory ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['SubCategory'] = $relationData;
			}
				else
				{
					$json['SubCategory'] 	= $list;
				}	
			}
		return $json;
	}
	public function asSearch() {
		$json = [ ];
		if($this->createdBy->asJson()){
			$json ['createUser'] = $this->createdBy->asJson();
		}else{
			$json ['createUser'] = [];
		}
		
		
		return $json;
	}

}
