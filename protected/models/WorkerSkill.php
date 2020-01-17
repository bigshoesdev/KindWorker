<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_worker_skill".
 *
 * @property integer $id
 * @property integer $sub_category_id
 * @property string $description
 * @property float $hourly_rate
 * @property integer $experience
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 * @property integer $travel_customer
 * @property integer $travel_me
 * @property Category $sub_category
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use app\models\SubCategory;
use app\models\User;

class WorkerSkill extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->id;
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
		return '{{%worker_skill}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'sub_category_id',
								'category_id',
								'created_on',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'sub_category_id',
								'category_id',
								'experience',
								'state_id',
                                'travel_me',
                                'travel_customer',
                                'type_id',
								'created_by_id' 
						],
						'integer' 
				],
                [
						[
								'hourly_rate',
						        'created_on',
								'updated_on',
								'description',
                                'delivery_time'
						],
						'safe' 
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
				'sub_category_id' => Yii::t ( 'app', 'Sub Category' ),
				'description' => Yii::t ( 'app', 'Description' ),
				'hourly_rate' => Yii::t ( 'app', 'Hourly Rate' ),
				'experience' => Yii::t ( 'app', 'Experience' ),
				'delivery_time' => Yii::t ( 'app', 'Delivery Time' ),
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
	public function getSubCategory() {
		return $this->hasOne ( SubCategory::className (), [ 
				'id' => 'sub_category_id' 
		] );
	}
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
	public function asJson($flag = false) {
		$json = [ ];
		if ($flag == false) {
			$json ['worker_skill_id'] = intval($this->id);
            $json ['category_id'] = intval($this->category_id);
            $json ['category_name'] = isset ( $this->category->title ) ? $this->category->title : '';
			if (! empty ( $this->category->image_file )) {
				$json ['image_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
						'user/download',
						'profile_file' => $this->category->image_file 
				] );
			}
			$json ['sub_category_id'] = intval($this->sub_category_id);
			$json ['sub_category_name'] = isset ( $this->subCategory ) ? $this->subCategory->title : '';
            $json ['travel_me'] = $this->travel_me;
            $json ['travel_customer'] = $this->travel_customer;
            $json ['state_id'] = intval($this->state_id);
			$json ['state_name'] = $this->getState ( $this->state_id );
			$json ['type_id'] = intval($this->type_id);
            $json ['contact_no'] = isset ( $this->createdBy->contact_no ) ? $this->createdBy->contact_no : '';
            $json ['address'] = isset ( $this->createdBy->address ) ? $this->createdBy->address : '';
            $json ['created_on'] = $this->created_on;
			$json ['commission'] = $this->getCommission ();
			$json ['avg_rating'] = floatval($this->getWorkerAvgRating($this->created_by_id));
		}
		$json ['description'] = $this->description;
		$json ['delivery_time'] = $this->delivery_time;
		$json ['hourly_rate'] = floatval($this->hourly_rate);
		$json ['experience'] = $this->experience;
		
		return $json;
	}
	public function asSearch($lat = null ,$long = null) {
		$json = [ ];
		
		$json ['id'] = intval($this->id);
		$json ['category_id'] = intval($this->category_id);
		$json ['sub_category_id'] = intval($this->sub_category_id);
		$json ['service_type'] = intval($this->category->type_id);
        $json ['description'] = $this->description;
		$json ['hourly_rate'] = floatval($this->hourly_rate);
		$json ['state_name'] = $this->getState ( $this->state_id );
		$json ['delivery_time'] = $this->delivery_time;
		$json ['experience'] = isset($this->experience)?intval($this->experience):0;
        $json ['travel_me'] = $this->travel_me;
        $json ['travel_customer'] = $this->travel_customer;
        $json ['category_name'] = isset ( $this->category->title ) ? $this->category->title : '';
		$json ['sub_category_name'] = isset ( $this->subCategory->title ) ? $this->subCategory->title : '';
		$json ['contact_no'] = isset ( $this->createdBy->contact_no ) ? $this->createdBy->contact_no : '';
		$json ['address'] = isset ( $this->createdBy->address ) ? $this->createdBy->address : '';
		$json ['commission'] = $this->getCommission ();
		$json ['review_count'] = intval($this->getWorkerReviewCount($this->created_by_id));
		$json ['avg_rating'] = floatval($this->getWorkerAvgRating ($this->created_by_id));
		$json ['about_me'] = $this->createdBy->about_me;
		if ($this->createdBy->asWorkerSearch ()) {
			$json ['createUser'] = $this->createdBy->asWorkerSearch ();
		} else {
			$json ['createUser'] = [ ];
		}
		if($lat != null && $long != null){
			$json['distance'] = $this->getEstimatedDistance ( $this->createdBy->latitude, $this->createdBy->longitude, $lat, $long, "0" );
		}
		return $json;
	}
	public static function getEstimatedDistance($lat1, $long1, $lat2, $long2) {
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&language=en";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_PROXYPORT, 3128 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		$response_a = json_decode ( $response, true );
		
		if (! empty ( $response_a ['rows'] [0] ['elements'] [0] ['distance'] )) {
			$dist = $response_a ['rows'] [0] ['elements'] [0] ['distance'] ['value'];
		} else {
			$dist = "0.0";
		}
		return $dist;
	}
	
	public function getCommission() {
		$model = Commission::find ()->one ();
		if (! empty ( $model )) {
			return $model;
		} else {
			return 0;
		}
	}

	public function getAvgRating($id) {
		$model = Rating::find ()->where ( [ 
				'created_by_id' => $id
		] )->orWhere ( [ 
				'user_id' => $id
		] )->average ( 'rate' );
		if ($model) {
			return $model;
		}else{
			return 0.0;
		}
	}

	public function getWorkerAvgRating($id) {
		$model = Rating::find ()->where ( [
				'user_id' => $id,
                'type_id' => Rating::TYPE_WORKER
		] )->average ( 'rate' );
		if ($model) {
			return $model;
		}else{
			return 0.0;
		}
	}

    public function getCustomerAvgRating($id) {
        $model = Rating::find ()->where ( [
            'user_id' => $id,
            'type_id' => Rating::TYPE_CUSTOMER
        ] )->average ( 'rate' );
        if ($model) {
            return $model;
        }else{
            return 0.0;
        }
    }

    public function getWorkerReviewCount($id) {
        $model = Rating::find ()->where ( [
            'user_id' => $id,
            'type_id' => Rating::TYPE_WORKER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return 0;
        }
    }
}
