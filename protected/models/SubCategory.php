<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_sub_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property Service[] $services
 * @property Category $category
 * @property User $createdBy
 * @property UserService[] $userServices
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\Service;
use app\models\Category;
use app\models\User;
use app\models\UserService;

class SubCategory extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->title;
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
		return '{{%sub_category}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'title',
								'category_id',
								'created_on',
								'updated_on',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
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
								'description' 
						],
						'safe' 
				],
				[ 
						[ 
								'title' 
						],
						'string',
						'max' => 255 
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
								'title' 
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
				'title' => Yii::t ( 'app', 'Sub Category' ),
				'description' => Yii::t ( 'app', 'Description' ),
				'category_id' => Yii::t ( 'app', 'Category' ),
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
	public function getTitle()
    {
        return isset ( $this->title ) ? $this->title : '';
    }

    public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['title'] = $this->title;
		$json ['description'] = $this->description;
		$json ['category_id'] = $this->category_id;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		$json ['is_used'] = $this->workerSkill ();
		//$json['worker_detail']=$this->workerDetail();
		
		return $json;
	}
	public function workerSkill() {
		$workerSkill = WorkerSkill::find ()->where ( [ 
				'sub_category_id' => $this->id,
				'created_by_id' => \yii::$app->user->id 
		] )->one ();
		if (! empty ( $workerSkill )) {
			return true;
		}
		return false;
	}
	
	public function workerDetail() {
		$workerSkill = WorkerSkill::find ()->where ( [
				'sub_category_id' => $this->id,
				'created_by_id' => \yii::$app->user->id
		] )->one ();
		if (! empty ( $workerSkill )) {
			return $workerSkill->asJson(true);
		}
		return '';
	}
}
