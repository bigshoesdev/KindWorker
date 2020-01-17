<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_rating".
 *
 * @property integer $id
 * @property string $rate
 * @property string $comment
 * @property integer $model_id
 * @property integer $model_type
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Rating extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->rate;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	
	const TYPE_WORKER = 0;
	const TYPE_CUSTOMER = 1;
	
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
				self::TYPE_WORKER => "Worker",
				self::TYPE_CUSTOMER => "Customer",
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
		return '{{%rating}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'rate',
								'created_on'
						],
						'required' 
				],
				[ 
						[
								'model_id',
								'state_id',
								'type_id',
								'user_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on' ,
								'tip_price'
						],
						'safe' 
				],
				[ 
						[ 
								'rate',
                                'model_type',
								'comment' 
						],
						'string',
						'max' => 100 
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
								'rate',
								'comment',
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
				'rate' => Yii::t ( 'app', 'Rate' ),
				'comment' => Yii::t ( 'app', 'Comment' ),
				'model_id' => Yii::t ( 'app', 'Model' ),
				'user_id' => Yii::t ( 'app', 'User' ),
				'model_type' => Yii::t ( 'app', 'Model Type' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'created_on' => Yii::t ( 'app', 'Created On' ),
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
	public function getJob() {
		return $this->hasOne ( Job::className (), [
				'id' => 'model_id'
		] );
	}
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
		$json ['rate'] = floatval($this->rate);
		$json ['comment'] = $this->comment;
		$json ['model_id'] = $this->model_id;
        $json ['model_type'] = $this->model_type;
        $json ['user_id'] = $this->user_id;
		$json ['user_name'] = isset($this->user->first_name)?$this->user->first_name:'';
		$json ['created_name'] = isset($this->createdBy->first_name)?$this->createdBy->first_name:'';
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		if (! empty ( $this->createdBy->profile_file )) {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'user/download/',
					'profile_file' => $this->createdBy->profile_file
			] );
		} else {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'themes/green/img/user.jpeg'
			] );
		}
		if($this->getJobData()){
			$json ['job_data'] = $this->getJobData();
		}
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
			}
		return $json;
	}
	public function getJobData(){
		$model = Job::find()->where(['id' => $this->model_id])->one();
		if(!empty($model)){
			return $model->asJson();
		}
	}

}
