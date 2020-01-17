<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_user_profile".
 *
 * @property integer $id
 * @property integer $age
 * @property integer $category_id
 * @property string $height
 * @property string $skills
 * @property string $document_file
 * @property string $education_qualification
 * @property string $experience
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property Category $category
 * @property User $createdBy
 */
namespace app\models;

use Yii;

class UserProfile extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->age;
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
		return '{{%user_profile}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'age',
								'category_id',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								
							
								'created_on',
								'updated_on',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'created_on',
								'updated_on' ,
								'skills',
								'education_qualification'
						],
						'safe' 
				],
				[ 
						[ 
								'height',
								'skills',
								'document_file',
								'education_qualification',
								'experience' 
						],
						'string',
						'max' => 255 
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
								'height',
								'skills',
								'document_file',
								'education_qualification',
								'experience' 
						],
						'trim' 
				],
				[ 
						[ 
								'document_file' 
						],
						'file',
						'skipOnEmpty' => true,
						'extensions' => 'png, jpg,jpeg' 
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
				'age' => Yii::t ( 'app', 'Age' ),
				'category_id' => Yii::t ( 'app', 'Category' ),
				'height' => Yii::t ( 'app', 'Height' ),
				'skills' => Yii::t ( 'app', 'Skills' ),
				'document_file' => Yii::t ( 'app', 'Document File' ),
				'education_qualification' => Yii::t ( 'app', 'Education Qualification' ),
				'experience' => Yii::t ( 'app', 'Experience' ),
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
		return $relations;
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['age'] = $this->age;
		$json ['category_id'] = $this->category_id;
		$json ['height'] = $this->height;
		$json ['skills'] = $this->skills;
		
			if (isset( $this->document_file)) {
				$json['document_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
						'user/download/',
						'profile_file' => $this->document_file
				] );
			}
			
			else {
				$json['document_file']='';
			}
		$json ['education_qualification'] = $this->education_qualification;
		$json ['experience'] = $this->experience;
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
		}
		return $json;
	}
	}
