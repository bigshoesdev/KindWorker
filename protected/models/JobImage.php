<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_job_image".
 *
 * @property integer $id
 * @property integer $job_id
 * @property string $first_file
 * @property string $second_file
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 * @property Job $job
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;
use app\models\Job;

class JobImage extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->job_id;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	const TYPE_BEFORE = 0;
	const TYPE_AFTER = 1;
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
				self::TYPE_BEFORE => "Before",
				self::TYPE_AFTER => "After",
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
		return '{{%job_image}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'job_id',
								'created_on',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'job_id',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on',
								'updated_on' 
						],
						'safe' 
				],
				[ 
						[ 
								'first_file',
								'second_file'
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
								'job_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => Job::className (),
						'targetAttribute' => [ 
								'job_id' => 'id' 
						] 
				],
				[ 
						[ 
								'first_file',
                                'second_file'
						],
						'trim' 
				],
				[ 
						[
                            'first_file',
                            'second_file'
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
				'job_id' => Yii::t ( 'app', 'Job' ),
				'first_file' => Yii::t ( 'app', 'First Image File' ),
				'second_file' => Yii::t ( 'app', 'Second Image File' ),
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
	public function getCreatedBy() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'created_by_id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
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
		$relations ['job_id'] = [ 
				'job',
				'Job',
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
		$json ['job_id'] = $this->job_id;
		if (isset ( $this->first_file )){
			$json ['first_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'user/download',
					'profile_file' => $this->first_file
			] );
		}
        if (isset ( $this->second_file )){
            $json ['second_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->second_file
            ] );
        }
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
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
			// Job $list = $this->getJob()->all();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['Job'] = $relationData;
			} else {
				$json ['Job'] = $list;
			}
		}
		return $json;
	}
}
