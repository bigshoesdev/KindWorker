<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_bid".
 *
 * @property integer $id
 * @property integer $job_id
 * @property string $bid_price
 * @property integer $deliver_in
 * @property string $date
 * @property string $paid
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

class Bid extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->job_id;
	}
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	
	const TYPE_PROGRESS= 0;
	const TYPE_AWARD= 1;
	const TYPE_REJECTED= 2;
	
	
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
            self::TYPE_PROGRESS => "Progress",
            self::TYPE_AWARD => "Award",
            self::TYPE_REJECTED => "Rejected"
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
		return '{{%bid}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'job_id',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'job_id',
								'deliver_in',
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
								'date',
								'description'
						],
						'safe' 
				],
                [
						[
								'bid_price'
						],
						'string',
						'max' => 128
				],
				[
						[
								'paid'
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
								'bid_price',
								'paid'
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
				'job_id' => Yii::t ( 'app', 'Job' ),
				'bid_price' => Yii::t ( 'app', 'Bid Price' ),
				'deliver_in' => Yii::t ( 'app', 'Deliver In' ),
				'paid' => Yii::t ( 'app', 'Paid' ),
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
	public function asJson($with_relations = true) {
		$json = [ ];
		$json ['bid_id'] = $this->id;
		$json ['job_id'] = $this->job_id;
		$json ['bid_price'] = floatval($this->bid_price);
		$json ['deliver_in'] = $this->deliver_in;
		$json ['paid'] = floatval($this->paid);
		$json ['description'] = $this->description;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		//$json ['hourly_rate'] = $this->getWorkerHourlyRate();

		$json['first_name'] = isset($this->createdBy->first_name)?$this->createdBy->first_name:'';
		$json['last_name'] = isset($this->createdBy->last_name)?$this->createdBy->last_name:'';
		$json['contact_no'] = isset($this->createdBy->contact_no)?$this->createdBy->contact_no:'';
		$json['address'] = isset($this->createdBy->address)?$this->createdBy->address:'';
		$json['latitude'] = isset($this->createdBy->latitude)?$this->createdBy->latitude:'';
		$json['longitude'] = isset($this->createdBy->longitude)?$this->createdBy->longitude:'';
		$json['review_count'] = intval($this->getWorkerReviewCount());
        $json['avg_rating'] = floatval($this->getWorkerAvgRating ());
        if (! empty ( $this->createdBy->profile_file )) {
			$json ['profile_file_worker'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'user/download/',
					'profile_file' => $this->createdBy->profile_file
			] );
		}
	//	$json['date'] = isset($this->booking->date)?$this->booking->date:'';
//		if ($with_relations) {
//
//			$list = $this->getJob()->all();
//
//			if (is_array ( $list )) {
//				$relationData = [ ];
//				foreach ( $list as $item ) {
//					$relationData = $item->asJson ();
//				}
//				$json ['Job'] = $relationData;
//			} else {
//				$json['Job'] 	= $list;
//				}
//			}
		return $json;
	}

    public function getWorkerReviewCount() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->created_by_id,
            'type_id' => Rating::TYPE_WORKER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return 0;
        }
    }

    public function getWorkerAvgRating() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->created_by_id,
            'type_id' => Rating::TYPE_WORKER
        ] )->average ( 'rate' );
        if ($model) {
            return $model;
        }else{
            return 0.0;
        }
    }

    public function getWorkerHourlyRate() {
//        $model = WorkerSkill::find ()->where ( [
//            'created_by_id' => $this->created_by_id,
//            'type_id' => $this->type_id
//        ])->one();
        $model = WorkerSkill::find ()->where ( [
            'created_by_id' => $this->created_by_id
        ])->one();
        if ($model) {
            return $model->hourly_rate;
        }else{
            return 0;
        }
    }

	public function asCustomer($with_relations = true) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['job_id'] = $this->job_id;
		$json ['bid_price'] = floatval($this->bid_price);
		$json ['deliver_in'] = $this->deliver_in;
		$json ['date'] = $this->date;
		$json ['paid'] = floatval($this->paid);
		$json ['description'] = $this->description;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		
//		$json['first_name'] = isset($this->createdBy->first_name)?$this->createdBy->first_name:'';
//		$json['last_name'] = isset($this->createdBy->last_name)?$this->createdBy->last_name:'';
//		$json['contact_no'] = isset($this->createdBy->contact_no)?$this->createdBy->contact_no:'';
//		$json['address'] = isset($this->createdBy->address)?$this->createdBy->address:'';
//		$json['latitude'] = isset($this->createdBy->latitude)?$this->createdBy->latitude:'';
//		$json['longitude'] = isset($this->createdBy->longitude)?$this->createdBy->longitude:'';
//		if (! empty ( $this->createdBy->profile_file )) {
//			$json ['profile_file_worker'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
//					'user/download/',
//					'profile_file' => $this->createdBy->profile_file
//			] );
//		}
		
		//	$json['date'] = isset($this->booking->date)?$this->booking->date:'';

		if ($with_relations) {
			
			$list = $this->getJob()->all();
            if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData = $item->asCustomerJobJson ();
				}
				$json ['Job'] = $relationData;
			} else {
				$json['Job'] = $list->asCustomerJobJson();
			}
		}
		return $json;
	}
	
}
