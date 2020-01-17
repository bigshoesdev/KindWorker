<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_job".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property string $description
 * @property string $title
 * @property integer $category_id
 * @property integer $sub_category_id
 * @property string $customjob_skills
 * @property string $slot_id
 * @property string $estimated_price
 * @property string $total_price
 * @property string $first_file
 * @property string $second_file
 * @property string $third_file
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property integer $cancel_by
 * @property integer $budget_type
 * @property integer $budget_id
 * @property string $date
 * @property integer $gig_quantity
 * @property integer $status
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property Bid[] $bs
 * @property Category $category
 * @property User $createdBy
 * @property SubCategory $subCategory
 * @property Message[] $messages
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\Bid;
use app\models\Category;
use app\models\User;
use app\models\SubCategory;
use app\models\Message;
use yii\base\Model;

class Job extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->title;
	}
    const JOB_TYPE_BOOOKED = 0;
    const JOB_TYPE_CUSTOM = 1;
    const STATE_IN_BID_PROGRESS = 0;
    const STATE_AWARDED = 1;
    const STATE_IN_PROGRESS = 2;
    const STATE_CANCEL = 3;
    const STATE_COMPLETE = 4;
    const STATE_DISPUTE = 5;
    const STATE_PAUSE = 6;
    const STATE_DELETE = 7;
    const TYPE_LOCAL = 0;
    const TYPE_REMOTE = 1;
    const BUDGET_TYPE_HOURLY = 0;
    const BUDGET_TYPE_FIXED = 1;
    const CANCEL_BY_NO = 0;
    const CANCEL_BY_WORKER = 1;
    const CANCEL_BY_CUSTOMER = 2;
	public static function getBudgetTypeOptions() {
		return [ 
				self::BUDGET_TYPE_HOURLY => "Hourly",
				self::BUDGET_TYPE_FIXED => "Fixed" 
		];
	}
	public function getBudgetType() {
		$list = self::getBudgetTypeOptions ();
		return isset ( $list [$this->budget_type] ) ? $list [$this->budget_type] : 'Not Defined';
	}
	public static function getJobTypeOptions() {
		return [ 
				self::JOB_TYPE_BOOOKED => "Booked Workers",
				self::JOB_TYPE_CUSTOM => "Custom jobs" 
		];
	}
	public function getJobType() {
		$list = self::getJobTypeOptions ();
		return isset ( $list [$this->job_type] ) ? $list [$this->job_type] : 'Not Defined';
	}
	public static function getStateOptions() {
		return [
				self::STATE_IN_BID_PROGRESS => "Bid In Progress",
				self::STATE_AWARDED => "Awarded",
				self::STATE_IN_PROGRESS => "In Progress",
				self::STATE_CANCEL => "Cancel",
				self::STATE_COMPLETE => "Complete" ,
				self::STATE_DISPUTE=> "Dispute",
				self::STATE_PAUSE=> "Pause",
				self::STATE_DELETE=> "Delete" 
		];
	}
	public function getState() {
		$list = self::getStateOptions ();
		return isset ( $list [$this->state_id] ) ? $list [$this->state_id] : 'Not Defined';
	}
	/*
	 * public function getStateBadge() {
	 * $list = [
	 * self::STATE_IN_BID_PROGRESS => "In Bid Progress",
	 * self::STATE_ACTIVE => "success",
	 * self::STATE_DELETED => "danger"
	 * ];
	 * return isset ( $list [$this->state_id] ) ? \yii\helpers\Html::tag ( 'span', $this->getState (), [
	 * 'class' => 'label label-' . $list [$this->state_id]
	 * ] ) : 'Not Defined';
	 * }
	 */
	public static function getTypeOptions() {
		return [ 
				self::TYPE_LOCAL => "Local",
				self::TYPE_REMOTE => "Remote" 
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
			    $this->date = date('Y-m-d');
			if (! isset ( $this->updated_on ))
				$this->updated_on = date ( 'Y-m-d H:i:s' );
                $this->date = date('Y-m-d');
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
		return '{{%job}}';
	}
	public function scenarios() {
		$scenarios = parent::scenarios ();
		$scenarios ['add-custom-job'] = [ 
				'description',
				'category_id',
				'sub_category_id',
				'customjob_skills',
				'title',
				'estimated_price',
				'address',
				'latitude',
				'longitude',
				'first_file',
				'second_file',
				'third_file',
				'budget_type',
				'budget_id',
				'type_id',
				'job_type' 
		];
        $scenarios ['add-custom-job-image'] = [
                'first_file',
                'second_file'
        ];

            $scenarios ['book-worker'] = [
				'description',
				'worker_id',
				'category_id',
				'sub_category_id',
				'slot_id',
				'title',
				'estimated_price',
				'total_price',
                'address',
				'latitude',
				'longitude',
				'type_id',
				'date',
				'gig_quantity',
				'job_type' 
		];
		return $scenarios;
	}
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								
								'category_id',
								'sub_category_id',
								'created_by_id' 
						],
						'required' 
				],
				[ 
						[ 
								'worker_id',
								'category_id',
								'sub_category_id',
								'cancel_by',
								'budget_type',
								'budget_id',
								'gig_quantity',
								'status',
								'state_id',
								'job_type',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'description' 
						],
						'string' 
				],
				[ 
						[ 
								'created_on',
								'updated_on',
                                'date',
						],
						'safe' 
				],
				[ 
						[ 
								'title',
								'address',
								'latitude',
								'longitude' ,
								
						],
						'string',
						'max' => 512 
				],
				[ 
						[ 
								'estimated_price',
								'total_price',
                                'customjob_skills',
								'first_file',
								'second_file',
								'third_file' 
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
								'title',
								'address',
								'latitude',
								'longitude',
								'date',
								'estimated_price',
								'total_price',
                                'customjob_skills',
								'first_file',
								'second_file',
								'third_file' 
						],
						'trim' 
				],
				[ 
						[ 
								'first_file' 
						],
						'file',
						'skipOnEmpty' => true,
						'extensions' => 'png, jpg,jpeg' 
				],
				[ 
						[ 
								'second_file' 
						],
						'file',
						'skipOnEmpty' => true,
						'extensions' => 'png, jpg,jpeg' 
				],
				[ 
						[ 
								'third_file' 
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
				'worker_id' => Yii::t ( 'app', 'Worker' ),
				'description' => Yii::t ( 'app', 'Description' ),
				'title' => Yii::t ( 'app', 'Title' ),
				'category_id' => Yii::t ( 'app', 'Category' ),
				'sub_category_id' => Yii::t ( 'app', 'Sub Category' ),
				'customjob_skills' => Yii::t ( 'app', 'Custom Job Skills' ),
				'slot_id' => Yii::t ( 'app', 'Slot' ),
				'estimated_price' => Yii::t ( 'app', 'Estimated Price' ),
				'total_price' => Yii::t ( 'app', 'Total Price' ),
				'first_file' => Yii::t ( 'app', 'First File' ),
				'second_file' => Yii::t ( 'app', 'Second File' ),
				'third_file' => Yii::t ( 'app', 'Third File' ),
				'address' => Yii::t ( 'app', 'Address' ),
				'latitude' => Yii::t ( 'app', 'Latitude' ),
				'longitude' => Yii::t ( 'app', 'Longitude' ),
				'budget_type' => Yii::t ( 'app', 'Budget Type' ),
				'budget_id' => Yii::t ( 'app', 'Budget ID' ),
				'date' => Yii::t ( 'app', 'Date' ),
				'gig_quantity' => Yii::t ( 'app', 'Gig Quantity' ),
				'status' => Yii::t ( 'app', 'Status' ),
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
	public function getWorker() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'worker_id' 
		] );
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
		Bid::deleteRelatedAll ( [ 
				'job_id' => $this->id 
		] );
		JobImage::deleteRelatedAll ( [ 
				'job_id' => $this->id 
		] );
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false, $id = null) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['date'] = $this->date;
        if($this->job_type==self::JOB_TYPE_BOOOKED)
            $json ['title'] = isset ( $this->sub_category_id ) ? $this->subCategory->title : '';
        else
            $json ['title'] = isset($this->title) ? $this->title : '';

        $json ['description'] = $this->description;

		$json ['worker_id'] = isset ( $this->worker_id ) ? $this->worker_id : null;
		$json ['worker_name'] = isset ( $this->worker->first_name ) ? $this->worker->first_name : '';
		$json ['worker_lat'] = isset ( $this->worker->latitude ) ? $this->worker->latitude : '';
		$json ['worker_long'] = isset ( $this->worker->longitude ) ? $this->worker->longitude : '';
		$json ['worker_contact_no'] = isset ( $this->worker->contact_no ) ? $this->worker->contact_no : '';
		$json ['review_count'] = $this->getWorkerReviewCount();
		$json ['avg_rating'] = floatval($this->getWorkerAverageRating());
        $json ['slot'] = $this->getSlotInfo();
		if (! empty ( $this->worker->profile_file )) {
			$json ['worker_image'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download',
					'profile_file' => $this->worker->profile_file 
			] );
		} else {
			$json ['worker_image'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'themes/green/img/user.jpeg' 
			] );
		}
		$json ['category_id'] = $this->category_id;
		$json ['category_name'] = isset ( $this->category_id ) ? $this->category->title : '';
		$json ['sub_category_id'] = $this->sub_category_id;
		$json ['sub_category_name'] = isset ( $this->sub_category_id ) ? $this->subCategory->title : '';
        $json ['customjob_skills'] = isset ( $this->customjob_skills ) ? $this->customjob_skills : '';
        $json ['service_type'] = $this->type_id;
        $json ['job_type'] = $this->job_type;
		$json ['estimated_price'] = isset($this->estimated_price)?floatval($this->estimated_price):0.0;
		$json ['total_price'] = isset($this->total_price)?floatval($this->total_price):0.0;
		if (isset ( $this->first_file ))
			$json ['first_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download',
					'profile_file' => $this->first_file 
			] );
		if (isset ( $this->second_file ))
			$json ['second_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download',
					'profile_file' => $this->second_file 
			] );
//		if (isset ( $this->third_file ))
//			$json ['third_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
//					'user/download',
//					'profile_file' => $this->third_file
//			] );
		$json ['address'] = $this->address;
		$json ['latitude'] = $this->latitude;
		$json ['longitude'] = $this->longitude;
		$json ['budget_type'] = $this->budget_type;
        $json ['budget_id'] = $this->budget_id;
        $json ['state_id'] = $this->state_id;
        $json ['state_name'] = $this->getState ( $this->state_id );
        $json['service_fee']= $this->getServiceFee();
		
		$json ['gig_quantity'] = $this->gig_quantity;
		
		$json ['status'] = $this->status;
		//$json ['cancel_by'] = $this->cancel_by;
		//$json ['is_rated'] = $this->jobRated ();
		
		$json ['hourly_rate'] = $this->getHourlyRate ();
        $json ['favorite_type'] = $this->getFavoritesState();
		$json ['created_on'] = $this->created_on;
        $json ['created_by_id'] = $this->created_by_id;
        $json ['create_user_name'] = isset ( $this->createdBy->first_name ) ? $this->createdBy->first_name : '';
		$json ['create_user_contact'] = isset ( $this->createdBy->contact_no ) ? $this->createdBy->contact_no : '';
		if (! empty ( $this->createdBy->profile_file )) {
			$json ['create_user_image'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download',
					'profile_file' => $this->createdBy->profile_file 
			] );
		}
		$json ['bid_count'] = intval($this->getBidCount ());
		//$json ['job_images'] = $this->getJobImages ();
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
			} else {
				$json ['SubCategory'] = $list;
			}
		}
		return $json;
	}

    public function asCustomerJson($with_relations = false, $id = null) {
        $json = [ ];
        $json ['id'] = $this->id;
        $json ['date'] = $this->date;
        if($this->job_type==self::JOB_TYPE_BOOOKED)
            $json ['title'] = isset ( $this->sub_category_id ) ? $this->subCategory->title : '';
        else
            $json ['title'] = isset($this->title) ? $this->title : '';
        $json ['description'] = $this->description;

//		$json ['counter'] = $this->getCounter ();
//		$json ['slot_title'] = $this->getSlot ();
//		$json ['slot_from_time'] = $this->getSlotTime ();
        $json ['review_count'] = $this->getCustomerReviewCount();
        $json ['avg_rating'] = floatval($this->getCustomerAverageRating());

        $json ['category_id'] = $this->category_id;
        $json ['category_name'] = isset ( $this->category_id ) ? $this->category->title : '';
        $json ['sub_category_id'] = $this->sub_category_id;
        $json ['sub_category_name'] = isset ( $this->sub_category_id ) ? $this->subCategory->title : '';
        $json ['customjob_skills'] = isset ( $this->customjob_skills ) ? $this->customjob_skills : '';
        $json ['service_type'] = $this->type_id;
        $json ['job_type'] = $this->job_type;
        $json ['slot'] = $this->getSlotInfo();
        $json ['estimated_price'] = isset($this->estimated_price)?floatval($this->estimated_price):0.0;
        $json ['total_price'] = isset($this->total_price)?floatval($this->total_price):0.0;
        if (isset ( $this->first_file ))
            $json ['first_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->first_file
            ] );
        if (isset ( $this->second_file ))
            $json ['second_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->second_file
            ] );
        $json ['address'] = $this->address;
        $json ['latitude'] = $this->latitude;
        $json ['longitude'] = $this->longitude;
        $json ['budget_type'] = $this->budget_type;
        $json ['budget_id'] = $this->budget_id;
        $json ['state_id'] = $this->state_id;
        $json ['state_name'] = $this->getState ( $this->state_id );
        $json['service_fee']= $this->getServiceFee();

        $json ['gig_quantity'] = $this->gig_quantity;

        $json ['status'] = $this->status;
        //$json ['cancel_by'] = $this->cancel_by;
        //$json ['is_rated'] = $this->jobRated ();

        $json ['created_on'] = $this->created_on;
        $json ['created_by_id'] = $this->created_by_id;
        $json ['create_user_name'] = isset ( $this->createdBy->first_name ) ? $this->createdBy->first_name : '';
        $json ['create_user_contact'] = isset ( $this->createdBy->contact_no ) ? $this->createdBy->contact_no : '';
        if (! empty ( $this->createdBy->profile_file )) {
            $json ['create_user_image'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->createdBy->profile_file
            ] );
        }
        $json ['bid_count'] = intval($this->getBidCount ());
        //$json ['job_images'] = $this->getJobImages ();
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
            } else {
                $json ['SubCategory'] = $list;
            }
        }
        return $json;
    }

    public function asCustomerJobJson($with_relations = false, $id = null) {
        $json = [ ];
        $json ['id'] = $this->id;
        $json ['date'] = $this->date;
        $json ['customer_review_count'] = $this->getCustomerReviewCount();
        $json ['customer_avg_rating'] = floatval($this->getCustomerAverageRating());

        $json ['description'] = $this->description;
        $json ['title'] = $this->title;
        $json ['category_id'] = $this->category_id;
        $json ['category_name'] = isset ( $this->category_id ) ? $this->category->title : '';
        $json ['sub_category_id'] = $this->sub_category_id;
        $json ['sub_category_name'] = isset ( $this->sub_category_id ) ? $this->subCategory->title : '';
        $json ['customjob_skills'] = isset ( $this->customjob_skills ) ? $this->customjob_skills : '';
        $json ['estimated_price'] = floatval($this->estimated_price);
        $json ['total_price'] = floatval($this->total_price);
        $json ['slot'] = $this->getSlotInfo();
        if (isset ( $this->first_file ))
            $json ['first_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->first_file
            ] );
        if (isset ( $this->second_file ))
            $json ['second_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->second_file
            ] );
        $json ['address'] = $this->address;
        $json ['latitude'] = $this->latitude;
        $json ['longitude'] = $this->longitude;
        $json ['budget_type'] = $this->budget_type;
        $json ['budget_id'] = $this->budget_id;
        $json ['budget'] = $this->getBudgetInfo();
        $json['service_fee']= $this->getServiceFee();

        $json ['gig_quantity'] = $this->gig_quantity;

        $json ['status'] = $this->status;
        //$json ['cancel_by'] = $this->cancel_by;
        $json ['state_id'] = $this->state_id;
        $json ['state_name'] = $this->getState ( $this->state_id );
        $json ['job_type'] = $this->job_type;
        $json ['service_type'] = $this->type_id;
        //$json ['is_rated'] = $this->jobRated ();

        //$json ['hourly_rate'] = $this->getHourlyRate ();

        $json ['created_on'] = $this->created_on;
        $json ['created_by_id'] = $this->created_by_id;
        $json ['create_user_name'] = isset ( $this->createdBy->first_name ) ? $this->createdBy->first_name : '';
        $json ['create_user_contact'] = isset ( $this->createdBy->contact_no ) ? $this->createdBy->contact_no : '';
        if (! empty ( $this->createdBy->profile_file )) {
            $json ['create_user_image'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download',
                'profile_file' => $this->createdBy->profile_file
            ] );
        }
        $json ['bid_count'] = intval($this->getBidCount ());
        //$json ['job_images'] = $this->getJobImages ();
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
            } else {
                $json ['SubCategory'] = $list;
            }
        }
        return $json;
    }

    public function getCounter() {
		$date = new \DateTime ( 'NOW' );
		$b = $this->date;
		$a = $this->getSlotTime ();
		$date2 = new \DateTime ( $b . $a );
		$diffSeconds = $date2->getTimestamp () - $date->getTimestamp ();
		return $diffSeconds;
	}
	public function getHourlyRate() {
		$model = WorkerSkill::find ()->where ( [ 
				'category_id' => $this->category_id,
				'created_by_id' => $this->worker_id
		] )->one ();
		if (! empty ( $model )) {
			return $model->hourly_rate;
		} else {
			return 0;
		}
	}

    public function getFavoritesState() {
        $model = Favorites::find ()->where ( [
            'job_id' => $this->id,
            'worker_id' => $this->worker_id,
            'created_by_id' => $this->created_by_id
        ] )->one ();
        if (! empty ( $model )) {
            return 1;
        } else {
            return 0;
        }
    }

	public function getBidCount() {
		$model = Bid::find ()->where ( [ 
				'job_id' => $this->id 
		] )->count ();
		return $model;
	}
	public function jobRated() {
		$model = Rating::find ()->where ( [ 
				'model_id' => $this->id ,
				'created_by_id' => \yii::$app->user->id
		] )->one ();
		if (! empty ( $model )) {
			return true;
		} else {
			return false;
		}
	}
	public function getSlot() {
		$model = Slot::find ()->where ( [ 
				'id' => $this->slot_id 
		] )->one ();
		
		if (! empty ( $model )) {
			return $model->title;
		} else {
			return '';
		}
	}
	public function getSlotTime() {
		$model = Slot::find ()->where ( [ 
				'id' => $this->slot_id 
		] )->one ();
		
		if (! empty ( $model )) {
			return $model->from;
		} else {
			return '';
		}
	}
	public function getJobImages() {
		$model = JobImage::find ()->where ( [ 
				'job_id' => $this->id 
		] )->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				return $list;
			}
			return $model->asJson ();
		} else {
			return [ ];
		}
	}
	public function getAverageRating() {
		$model = Rating::find ()->where ( [ 
				'created_by_id' => $this->worker_id
		] )->orWhere ( [ 
				'user_id' => $this->worker_id
		] )->average ( 'rate' );
		if (! empty ( $model )) {
			return $model;
		}
		return 0.0;
	}

    public function getWorkerAverageRating() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->worker_id,
            'type_id' => Rating::TYPE_WORKER
        ] )->average ( 'rate' );
        if (! empty ( $model )) {
            return $model;
        }
        return 0.0;
    }

    public function getWorkerReviewCount() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->worker_id,
            'type_id' => Rating::TYPE_WORKER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return 0;
        }
    }

    public function getCustomerReviewCount() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->created_by_id,
            'type_id' => Rating::TYPE_CUSTOMER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return 0;
        }
    }

    public function getCustomerAverageRating() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->created_by_id,
            'type_id' => Rating::TYPE_CUSTOMER
        ] )->average ( 'rate' );
        if (! empty ( $model )) {
            return $model;
        }
        return 0.0;
    }

	public function getServiceFee() {
		$model = ServiceAmount::find ()->one();
		if (! empty ( $model )) {
			return $model->service_fee;
		} else{
			return 0;
		}
	}

	public function getSlotInfo() {
	    $model = AvailabilitySlot::find ()->where([
	        'job_id' => $this->id
        ])->one();

	    if(! empty($model)) {
	        return $model->asJson();
        } else {
	        return array();
        }
    }

    public function getBudgetInfo() {
	    $model = Budget::find () -> where( [
	        'id' => $this->budget_id
        ])->one();


        if(! empty($model)) {
            return $model->asJson();
        } else {
            return array();
        }
    }
}
