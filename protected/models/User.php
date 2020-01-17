<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $social_type
 * @property string $social_id
 * @property string $date_of_birth
 * @property string $is_approved
 * @property integer $gender
 * @property string $about_me
 * @property string $certification
 * @property string $contact_no
 * @property string $address
 * @property string $address2
 * @property string $latitude
 * @property string $longitude
 * @property string $approval_address
 * @property string $business_trade_name
 * @property string $federal_tax_classification
 * @property string $tax_id
 * @property integer $total_worker
 * @property string $city
 * @property string $country
 * @property string $zipcode
 * @property string $profile_file
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $status
 * @property integer $type_id
 * @property integer $vacation_mode
 * @property string $braintree_id
 * @property string $last_visit_time
 * @property string $last_action_time
 * @property string $last_password_change
 * @property integer $login_error_count
 * @property string $activation_key
 * @property string $created_on
 * @property string $update_time
 * @property integer $created_by_id ===Relative data ===
 *          
 * @property Comment[] $comments
 */
namespace app\models;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class User extends \app\components\TActiveRecord implements \yii\web\IdentityInterface {
	const IS_FILL = 1;
	const NOT_FILL = 0;
	const SOCIAL_FACEBOOK = 0;
	const SOCIAL_TWITTER = 1;
	const SOCIAL_GOOGLE = 2;
	const STATE_INACTIVE = 0;
	const STATE_ACTIVE = 1;
	const STATE_DELETED = 2;
	const STEP_ZERO = 0;
	const STEP_ONE = 1;
	const STEP_TWO = 2;
	const STEP_THREE = 3;
	const STEP_FOUR = 4;
	const STEP_FIVE = 5;
	const MALE = 0;
	const FEMALE = 1;
	const ROLE_ADMIN = 0;
	const ROLE_CUSTOMER = 1;
	const ROLE_WORKER = 2;
	const ROLE_SUBADMIN = 3;
	const TYPE_ON = 0;
	const TYPE_OFF = 1;
	const LOCAL_SERVICE = 0;
	const REMOTE_SERVICE = 1;
	const BOTH_SERVICE = 2;
	const STATUS_BANNED = 1;
	const STATUS_DENY = 3;
	const STATUS_APPROVED = 2;
	const STATUS_NORMAL = 0;
	const STATUS_ADDRESS_APPROVAL = 4;
	const VACATION_OFF = 0;
	const VACATION_ON = 1;
	public $valid = true;
	public $confirm_password;
	public $newPassword;
	public $oldPassword;
	public $addPassword;
	public $device_token;
	public $device_type;
	public $token;
	public function __toString() {
		return ( string ) $this->full_name;
	}
	public static function getGenderOptions($id = null) {
		$list = array (
				self::MALE => "Male",
				self::FEMALE => "Female" 
		);
		if ($id === null)
			return $list;
		return isset ( $list [$id] ) ? $list [$id] : 'Not Defined';
	}
	public static function getRoleOptions($id = null) {
		$list = array (
				self::ROLE_ADMIN => "Admin",
				self::ROLE_CUSTOMER => "Customer",
				self::ROLE_WORKER => "Worker",
				self::ROLE_SUBADMIN => "Sub_Admin"
		);
		if ($id === null)
			return $list;
		return isset ( $list [$id] ) ? $list [$id] : 'Not Defined';
	}
    public static function getSocialOptions($id = null) {
        $list = array (
            self::SOCIAL_FACEBOOK => "facebook",
            self::SOCIAL_TWITTER => "twitter",
            self::SOCIAL_GOOGLE => "google"
        );
        if ($id === null)
            return $list;
        return isset ( $list [$id] ) ? $list [$id] : 'Not Defined';
    }
	public static function getStatusOptions($id = null) {
		$list = array (
				self::STATUS_DENY => "Denied",
				self::STATUS_APPROVED => "Approved",
                self::STATUS_BANNED => "Banned",
                self::STATUS_NORMAL => "Normal"
		);
		if ($id === null)
			return $list;
		return isset ( $list [$id] ) ? $list [$id] : 'Not Defined';
	}
	public static function getAdminStatusOptions($id = null) {
		$list = array (
				self::STATUS_DENY => "Denied",
				self::STATUS_BANNED => "Banned",
				self::STATUS_APPROVED => "Approved",
				self::STATUS_NORMAL => "Normal",
				self::STATUS_ADDRESS_APPROVAL => "Address Approval Request" 
		);
		if ($id === null)
			return $list;
		return isset ( $list [$id] ) ? $list [$id] : 'Not Defined';
	}
	public static function getStateOptions() {
		return [ 
				self::STATE_INACTIVE => "Inactive",
				self::STATE_ACTIVE => "Active",
				self::STATE_DELETED => "Deleted" 
		];
	}
	public static function getUserAction() {
		return [ 
				self::STATE_INACTIVE => "In-active",
				self::STATE_ACTIVE => "Actived",
				self::STATE_BANNED => "Ban",
				self::STATE_DELETED => "Delete" 
		];
	}

	public function getState() {
		$list = self::getStateOptions ();
		return isset ( $list [$this->state_id] ) ? $list [$this->state_id] : 'Not Defined';
	}
	public function getStateBadge() {
		$list = [ 
				self::STATE_INACTIVE => "default",
				self::STATE_ACTIVE => "success",
				self::STATE_DELETED => "danger" 
		];
		// return \yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]);
		return \yii\helpers\Html::tag ( 'span', $this->getState (), [ 
				'class' => 'label label-' . $list [$this->state_id] 
		] );
	}
	public static function getTypeOptions() {
		return [ 
				"TYPE1",
				"TYPE2",
				"TYPE3" 
		];
	}
	public static function getStepAction() {
		return [ 
				self::STEP_ZERO => "zero",
				self::STEP_ONE => "one",
				self::STEP_TWO => "two",
				self::STEP_THREE => "three",
				self::STEP_FOUR => "four",
				self::STEP_FIVE => "five" 
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
		return '{{%user}}';
	}
	public function scenarios() {
		$scenarios = parent::scenarios ();
		$scenarios ['add'] = [ 
				//'full_name',
				'first_name',
				'last_name',
				'email',
				'contact_no',
				'confirm_password',
				'addPassword',
				'profile_file' 
		];
		
		$scenarios ['signup'] = [ 
				'first_name',
				'last_name',
				'email',
				'password',
				'confirm_password' 
		];
		$scenarios ['changepassword'] = [ 
				'newPassword',
				//'oldPassword',
				'confirm_password' 
		];
		$scenarios ['resetpassword'] = [ 
				'password' 
		];
		$scenarios ['update'] = [ 
				'first_name',
				'last_name',
				'email',
				'contact_no',
				'profile_file' 
		];
		$scenarios ['token_request'] = [ 
				'email' 
		];
		$scenarios ['update_api'] = [ 
				'first_name',
				'last_name',
				'address',
				'country',
				'date_of_birth',
				'email',
				'contact_no',
				'profile_file' 
		];
		$scenarios ['update_customer'] = [ 
				'first_name',
				'last_name',
				'contact_no' 
		];
		
		$scenarios ['step1'] = [ 
				'is_service',
				'address',
				'address2',
				'country',

				'state',
				'city',
				'contact_no',
				'date_of_birth',
				'zipcode' 
		];
		$scenarios ['step3'] = [ 
				'profile_file',
				'document_file' 
		];
		$scenarios ['step4'] = [ 
				'account_no',
				'routing_no' 
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
								'first_name',
                                'last_name',
                                'password',
                                'email'
						],
						'required'
				],
				[ 
						[ 
								'password',
								'newPassword'
						],
						'app\components\TPasswordValidator',
						'length' => 8 
				],
				[
						'role_id',
						'in',
						'range' => array_keys ( self::getRoleOptions () ) 
				],
				[ 
						'state_id',
						'in',
						'range' => array_keys ( self::getStateOptions () ) 
				],
				[ 
						'type_id',
						'in',
						'range' => array_keys ( self::getTypeOptions () ) 
				],
                [
						'social_type',
						'in',
						'range' => array_keys ( self::getSocialOptions() )
				],
                [
						[ 
								'full_name',
								'first_name',
								'last_name' 
						],
						'app\components\TNameValidator' 
				],
				[ 
						[ 
								'full_name',
								'city',
								'country',
								'address',
								'address2'
						],
						'trim' 
				],
				[ 
						[ 
								'full_name',
								'city',
								'country',
								'address',
								'address2'
						],
						'filter',
						'filter' => function ($data) {
							return ucwords ( $data );
						} 
				],
				
				[ 
						[ 
								'city',
								'country' 
						],
						'match',
						'pattern' => '/^[a-z ]*$/i' 
				],
				[ 
						'email',
						'email'
				],
				[ 
						'email',
						'unique' 
				],
				
				[ 
						[ 
								'zipcode',
								'gender',
								'tos',
								'role_id',
								'is_service',
								'state_id',
                                'social_type',
                                'total_worker',
                                'created_by_id'
						],
						'integer' 
				],
				[ 
						[ 
								'date_of_birth',
								'created_on',
								'device_type',
								'device_token',
								'status',
								'is_fill',
								'vacation_mode',
								//'is_service',
								'last_search',
								'security_check_no',
								'message',
								'state',
								'step',
								'stripe_customer_id',
								'social_id',
								'token',
								'approval_address',
								'approval_address_type' 
						],
						'safe' 
				],
				
				[ 
						'password',
						'required',
						'on' => 'resetpassword' 
				],
				[ 
						
						[ 
								'email' 
						],
						'required',
						'on' => [ 
								'recover' 
						] 
				],
				[ 
						
						[ 
								'email',
								'password',
								'confirm_password' 
						],
						'required',
						'on' => [ 
								'register' 
						] 
				],
				[ 
						
						[ 
								'email' 
						],
						'required',
						'on' => [ 
								'token_request' 
						] 
				],
				[ 
						
						[ 
								'email' 
						],
						'required',
						'on' => [ 
								'recover' 
						] 
				],
				[ 
						[ 
								'email',
								'full_name',
								'status' 
						],
						'required',
						'on' => [ 
								'update' 
						] 
				],
				[ 
						[ 
								'status'
						],
						'required',
						'on' => [ 
								'update-approval' 
						] 
				],
				[ 
						[ 
								'newPassword',
								'oldPassword',
								'confirm_password'
						],
						'required',
						'on' => [ 
								'changepassword' 
						] 
				],
                [
						'confirm_password',
						'compare',
						'compareAttribute' => 'newPassword',
						'message' => "Passwords don't match",
						'on' => [
								'changepassword'
						]
				],
                [
                    [
                        'first_name',
                        'last_name',
                        'email',
                        'contact_no',
                        //'profile_file',
                        'addPassword',
                        'confirm_password'
                    ],
                    'required',
                    'on' => [
                        'add'
                    ]
                ],
                [
                    'confirm_password',
                    'compare',
                    'compareAttribute' => 'addPassword',
                    'message' => "Passwords don't match",
                    'on' => [
                        'add'
                    ]
                ],
                [
						'confirm_password',
						'compare',
						'compareAttribute' => 'password',
						'message' => "Passwords don't match",
						'on' => [
								'signup'
						]
				],
                [
						[ 
								'profile_file' 
						],
						'file',
						'skipOnEmpty' => true,
						'extensions' => 'png, jpg,jpeg' 
				],
				[ 
						[ 
								'full_name',
								'last_name',
								'first_name',
								'email',
								'password',
								'confirm_password' 
						],
						'required',
						'on' => [ 
								'signup' 
						] 
				],
				
				[ 
						[ 
								'about_me',
                                'certification',
                                'braintree_id'
						],
						'string' 
				],
				[ 
						[ 
								'full_name' 
						],
						'string',
						'max' => 55 
				],
				[ 
						[ 
								'email',
                            'latitude',
                            'longitude'
						],
						'string',
						'max' => 128 
				],
				[ 
						[ 
								// 'timezone',
								'profile_file',
								'activation_key',
								'contact_no' 
						],
						'string',
						'max' => 512 
				],
				[ 
						'password',
						'string' 
				],
				[ 
						[ 
								'contact_no' 
						],
						'integer' 
				],
				[ 
						[ 
								'address',
								'address2',
								'city',
								'business_trade_name',
								'federal_tax_classification',
								'tax_id',
								'country'
						],
						'string',
						'max' => 256 
				] 
		];
	}
	
	// public function checkFormat($attribute, $params)
	// {
	// // no real check at the moment to be sure that the error is triggered
	// if($this->status==''&&$this->status==null)
	// {
	// $this->addError($attribute, Yii::t('user', 'please select the right choice'));
	// }
	
	// }
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'full_name' => Yii::t ( 'app', 'Full Name' ),
				'first_name' => Yii::t ( 'app', 'First Name' ),
				'last_name' => Yii::t ( 'app', 'Last Name' ),
				'email' => Yii::t ( 'app', 'Email' ),
				'password' => Yii::t ( 'app', 'Password' ),
				'addPassword' => Yii::t ( 'app', 'Password' ),
				'confirm_password' => Yii::t ( 'app', 'Confirm Password' ),
				'date_of_birth' => Yii::t ( 'app', 'Date Of Birth' ),
				'gender' => Yii::t ( 'app', 'Gender' ),
				'is_fill' => Yii::t ( 'app', 'Is Fill' ),
				'vacation_mode' => Yii::t ( 'app', 'Vacation Mode' ),
				'about_me' => Yii::t ( 'app', 'About Me' ),
				'certification' => Yii::t ( 'app', 'Certification' ),
				'braintree' => Yii::t ( 'app', 'Braintree ID' ),
				'contact_no' => Yii::t ( 'app', 'Contact No.' ),
				'message' => Yii::t ( 'app', 'message' ),
				'address' => Yii::t ( 'app', 'Address' ),
				'address2' => Yii::t ( 'app', 'Address2' ),
				'state' => Yii::t ( 'app', 'State' ),
				'latitude' => Yii::t ( 'app', 'Latitude' ),
				'longitude' => Yii::t ( 'app', 'Longitude' ),
				'city' => Yii::t ( 'app', 'City' ),
				'country' => Yii::t ( 'app', 'Country' ),
				'zipcode' => Yii::t ( 'app', 'Zipcode' ),
				'last_search' => Yii::t ( 'app', 'Last Search' ),
				'step' => Yii::t ( 'app', 'Step' ),
				'language' => Yii::t ( 'app', 'Language' ),
				'profile_file' => Yii::t ( 'app', 'Profile File' ),
				'status' => Yii::t ( 'app', 'status' ),
				'tos' => Yii::t ( 'app', 'Tos' ),
				'role_id' => Yii::t ( 'app', 'Role' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'last_visit_time' => Yii::t ( 'app', 'Last Visit Time' ),
				'last_action_time' => Yii::t ( 'app', 'Last Action Time' ),
				'last_password_change' => Yii::t ( 'app', 'Last Password Change' ),
				'login_error_count' => Yii::t ( 'app', 'Login Error Count' ),
				'activation_key' => Yii::t ( 'app', 'Activation Key' ),
				'timezone' => Yii::t ( 'app', 'Timezone' ),
				'created_on' => Yii::t ( 'app', 'Created On' ),
				'updated_on' => Yii::t ( 'app', 'Updated On' ),
				'created_by_id' => Yii::t ( 'app', 'Created By' ) 
		];
	}
	public function getAuthSessions() {
		return $this->hasMany ( AuthSession::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments() {
		return $this->hasMany ( Comment::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getLoginHistories() {
		return $this->hasMany ( LoginHistory::className (), [ 
				'user_id' => 'id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getPages() {
		return $this->hasMany ( Page::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	public function getProfile() {
		return $this->hasMany ( UserProfile::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	public function getWorkerSkills() {
		return $this->hasMany ( WorkerSkill::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	public function getBank() {
		return $this->hasMany ( Bank::className (), [ 
				'created_by_id' => 'id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		$relations ['created_by_id'] = [ 
				'authSessions',
				'AuthSession',
				'id' 
		];
		$relations ['created_by_id'] = [ 
				'pages',
				'Page',
				'id' 
		];
		$relations ['user_id'] = [ 
				'loginHistories',
				'LoginHistory',
				'id' 
		];
		$relations ['user_id'] = [ 
				'comment',
				'Comment',
				'id' 
		];
		
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		return $relations;
	}
	public function beforeDelete() {
        Favorites::deleteRelatedAll ( [
            'created_by_id' => $this->id
        ] );
        UserAddress::deleteRelatedAll ( [
            'created_by_id' => $this->id
        ] );
		Page::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Comment::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		
		LoginHistory::deleteRelatedAll ( [ 
				'user_id' => $this->id 
		] );
		
		AuthSession::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		UserProfile::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Bank::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Category::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		SubCategory::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Job::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		WorkerSkill::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Transaction::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		AvailabilitySlot::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Bid::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Notification::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		Rating::deleteRelatedAll ( [ 
				'created_by_id' => $this->id 
		] );
		AppNotification::deleteRelatedAll ( [ 
				'create_user_id' => $this->id 
		] );
		AppNotification::deleteRelatedAll ( [ 
				'user_id' => $this->id 
		] );
		JobImage::deleteRelatedAll ( [
				'created_by_id' => $this->id
		] );
		return parent::beforeDelete ();
	}
	public static function dailySignups() {
		$singups_count = User::find ()->where ( [ 
				'DATE(create_on)' => date ( "Y-m-d" ) 
		] )->count ();
		return $singups_count;
	}
	public function sendRegistrationMailtoAdmin() {
		$sub = 'User Registerd Successfully';
		$from = $this->email;
		$message = \yii::$app->view->renderFile ( '@app/mail/newUser.php', [ 
				'user' => $this 
		] );
		$allAdmins = self::find ()->where ( [ 
				'role_id' => self::ROLE_ADMIN 
		] );
		foreach ( $allAdmins->batch () as $admins ) {
			foreach ( $admins as $admin ) {
				$to = $admin->email;
				$mail = new EmailQueue ();
				$mail->sendNow ( $to, $message, $from, $sub );
			}
		}
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['full_name'] = isset ($this->full_name)?$this->full_name:'';
		$json ['first_name'] = isset($this->first_name)?$this->first_name:'';
		$json ['last_name'] = isset ( $this->last_name ) ? $this->last_name : '';
		$json ['approval_address'] = isset ( $this->approval_address ) ? $this->approval_address : '';
		$json ['email'] = isset($this->email)?$this->email:'';
		$json ['date_of_birth'] = isset($this->date_of_birth)? $this->date_of_birth:'';
		$json ['is_fill'] = ! empty ( $this->is_fill ) ? $this->is_fill : 0;
		$json ['vacation_mode'] = ! empty ( $this->vacation_mode ) ? $this->vacation_mode : User::VACATION_OFF;
		$json ['gender'] = $this->gender;
		$json ['about_me'] = isset($this->about_me)?$this->about_me:'';
		$json ['certification'] = isset($this->certification)?$this->certification:'';
		$json ['stripe_customer_id'] = isset($this->stripe_customer_id)?$this->stripe_customer_id:'';
		$json ['state'] = isset($this->state)?$this->state:'';
		$json ['contact_no'] = isset($this->contact_no)?$this->contact_no:'';
		$json ['address'] = isset($this->address)?$this->address:'';
		$json ['address2'] = isset($this->address)?$this->address2:'';
		$json ['latitude'] = isset($this->latitude)?$this->latitude:'';
		$json ['longitude'] = isset($this->longitude)?$this->longitude:'';
		$json ['city'] = isset($this->city)?$this->city:'';
		$json ['country'] = isset($this->country)?$this->country:'';
		$json ['zipcode'] = isset($this->zipcode)?$this->zipcode:'';
		$json ['last_search'] = isset($this->last_search)?$this->last_search:'';
		$json ['language'] = isset($this->language)?$this->language:'';
		if (! empty ( $this->profile_file )) {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'user/download/',
					'profile_file' => $this->profile_file
			] );
		} else {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
					'themes/green/img/user.jpeg'
			] );
		}
		$json ['status'] = $this->status;
		$json ['status_options'] = $this->getStatusOptions ( $this->status );
		$json ['tos'] = $this->tos;
		$json ['is_service'] = isset($this->is_service)?$this->is_service:0;
		$json ['role_id'] = $this->role_id;
		$json ['role_name'] = $this->getRoleOptions ( $this->role_id );
		$json ['security_check_no'] = isset($this->security_check_no)?$this->security_check_no:0;

		if ($this->status == User::STATUS_BANNED) {
			$json ['message'] = 'You have been banned by Admin' . ' ' . $this->message;
		} elseif ($this->status == User::STATUS_DENY) {
			$json ['message'] = 'You have been denied by Admin' . ' ' . $this->message;
		} elseif ($this->status == User::STATUS_APPROVED) {
			$json ['message'] = 'Your Profile has been approved by Admin' . ' ' . $this->message;
		} else {
			$json ['message'] = $this->message;
		}
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['step'] = $this->step;
		$json ['last_visit_time'] = isset($this->last_visit_time)?$this->last_visit_time:'';
		$json ['last_action_time'] = isset($this->last_action_time)?$this->last_action_time:'';
		$json ['last_password_change'] = isset($this->last_password_change)?$this->last_password_change:'';
		$json ['login_error_count'] = isset($this->login_error_count)?$this->login_error_count:'';
		$json ['timezone'] = isset($this->timezone)?$this->timezone:'';
		$json ['created_on'] = isset($this->created_on)?$this->created_on:'';
		$json ['created_by_id'] = $this->created_by_id;
		if (! empty ( $this->getWorkerSkillDetail () )) {
			$json ['worker_detail'] = $this->getWorkerSkillDetail ();
		} else {
			$json ['worker_detail'] = '';
		}

		if ($with_relations) {
			if (! empty ( $this->getBankDetail () )) {
				$json ['bank'] = $this->getBankDetail ();
			}
			$json ['bank'] = ! empty ( $this->getBankDetail () ) ? $this->getBankDetail () : '';

			if (! empty ( $this->getUserProfileDetail () )) {
				$json ['profile'] = $this->getUserProfileDetail ();
			}
		}

		return $json;
	}

    public function asWorkerDetailJson($with_relations = false) {
        $json = [ ];
        $json ['id'] = $this->id;
        $json ['full_name'] = isset ($this->full_name)?$this->full_name:'';
        $json ['first_name'] = isset($this->first_name)?$this->first_name:'';
        $json ['last_name'] = isset ( $this->last_name ) ? $this->last_name : '';
        $json ['approval_address'] = isset ( $this->approval_address ) ? $this->approval_address : '';
        $json ['email'] = isset($this->email)?$this->email:'';
        $json ['date_of_birth'] = isset($this->date_of_birth)? $this->date_of_birth:'';
        $json ['is_fill'] = ! empty ( $this->is_fill ) ? $this->is_fill : 0;
        $json ['vacation_mode'] = ! empty ( $this->vacation_mode ) ? $this->vacation_mode : User::VACATION_OFF;
        $json ['gender'] = $this->gender;
        $json ['about_me'] = isset($this->about_me)?$this->about_me:'';
        $json ['certification'] = isset($this->certification)?$this->certification:'';
        $json ['state'] = isset($this->state)?$this->state:'';
        $json ['contact_no'] = isset($this->contact_no)?$this->contact_no:'';
        $json ['address'] = isset($this->address)?$this->address:'';
        $json ['address2'] = isset($this->address)?$this->address2:'';
        $json ['latitude'] = isset($this->latitude)?$this->latitude:'';
        $json ['longitude'] = isset($this->longitude)?$this->longitude:'';
        $json ['city'] = isset($this->city)?$this->city:'';
        $json ['country'] = isset($this->country)?$this->country:'';
        $json ['zipcode'] = isset($this->zipcode)?$this->zipcode:'';
        $json ['last_search'] = isset($this->last_search)?$this->last_search:'';
        $json ['language'] = isset($this->language)?$this->language:'';
        if ($this->getWorkerAvgRating ())
            $json ['overall_avg'] = floatval($this->getWorkerAvgRating ());
        else
            $json ['overall_avg'] = 0.0;
        $json ['review_count'] = intval($this->getWorkerReviewCount ());
        if (! empty ( $this->profile_file )) {
            $json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download/',
                'profile_file' => $this->profile_file
            ] );
        } else {
            $json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'themes/green/img/user.jpeg'
            ] );
        }
        $json ['status'] = $this->status;
        $json ['status_options'] = $this->getStatusOptions ( $this->status );
        $json ['tos'] = $this->tos;
        $json ['is_service'] = isset($this->is_service)?$this->is_service:0;
        $json ['role_id'] = $this->role_id;
        $json ['role_name'] = $this->getRoleOptions ( $this->role_id );
        $json ['security_check_no'] = isset($this->security_check_no)?$this->security_check_no:0;

        if ($this->status == User::STATUS_BANNED) {
            $json ['message'] = 'You have been banned by Admin' . ' ' . $this->message;
        } elseif ($this->status == User::STATUS_DENY) {
            $json ['message'] = 'You have been denied by Admin' . ' ' . $this->message;
        } elseif ($this->status == User::STATUS_APPROVED) {
            $json ['message'] = 'Your Profile has been approved by Admin' . ' ' . $this->message;
        } else {
            $json ['message'] = $this->message;
        }
        $json ['state_id'] = $this->state_id;
        $json ['type_id'] = $this->type_id;
        $json ['step'] = $this->step;
        $json ['last_visit_time'] = isset($this->last_visit_time)?$this->last_visit_time:'';
        $json ['last_action_time'] = isset($this->last_action_time)?$this->last_action_time:'';
        $json ['last_password_change'] = isset($this->last_password_change)?$this->last_password_change:'';
        $json ['login_error_count'] = isset($this->login_error_count)?$this->login_error_count:'';
        $json ['timezone'] = isset($this->timezone)?$this->timezone:'';
        $json ['created_on'] = isset($this->created_on)?$this->created_on:'';
        $json ['created_by_id'] = $this->created_by_id;
        if (! empty ( $this->getWorkerSkillDetail () )) {
            $json ['worker_detail'] = $this->getWorkerSkillDetail ();
        } else {
            $json ['worker_detail'] = [];
        }
        if (! empty ( $this->getWorkerReviews())) {
            $json ['review_detail'] = $this->getWorkerReviews ();
        } else {
            $json ['review_detail'] = [];
        }

        if (! empty ( $this->getWorkerPortfolios())) {
            $json ['portfolio_detail'] = $this->getWorkerPortfolios ();
        } else {
            $json ['portfolio_detail'] = [];
        }

        if ($with_relations) {
            if (! empty ( $this->getBankDetail () )) {
                $json ['bank'] = $this->getBankDetail ();
            }
            $json ['bank'] = ! empty ( $this->getBankDetail () ) ? $this->getBankDetail () : '';

            if (! empty ( $this->getUserProfileDetail () )) {
                $json ['profile'] = $this->getUserProfileDetail ();
            }
        }

        return $json;
    }

	public function asWorkerSearch($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['full_name'] = $this->full_name;
		$json ['status'] = $this->status;
		$json ['status_options'] = $this->getStatusOptions ( $this->status );
		$json ['first_name'] = $this->first_name;
		$json ['last_name'] = isset ( $this->last_name ) ? $this->last_name : '';
		$json ['email'] = $this->email;
		$json ['zipcode'] = $this->zipcode;
		$json ['last_search'] = $this->last_search;
		$json ['latitude'] = $this->latitude;
		$json ['longitude'] = $this->longitude;
		if (! empty ( $this->profile_file )) {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download/',
					'profile_file' => $this->profile_file 
			] );
		} else {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'themes/green/img/user.jpeg' 
			] );
		}
		$json ['role_id'] = $this->role_id;
		
		return $json;
	}
	public function asAddress($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['status'] = $this->status;
		$json ['status_options'] = $this->getStatusOptions ( $this->status );
		$json ['first_name'] = $this->first_name;
		$json ['last_zip'] = $this->last_zip;
		$json ['last_search'] = $this->last_search;
		$json ['last_latitude'] = $this->last_latitude;
		$json ['last_longitude'] = $this->last_longitude;
		$json ['role_id'] = $this->role_id;
		
		return $json;
	}
	public function asWorkerJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['status'] = $this->status;
		$json ['status_options'] = $this->getStatusOptions ( $this->status );
		$json ['full_name'] = $this->full_name;
		$json ['first_name'] = $this->first_name;
		$json ['last_name'] = isset ( $this->last_name ) ? $this->last_name : '';
		$json ['email'] = $this->email;
		$json ['zipcode'] = $this->zipcode;
		$json ['last_search'] = $this->last_search;
        $json ['about_me'] = isset($this->about_me)?$this->about_me:'';
        $json ['certification'] = isset($this->certification)?$this->certification:'';
        $json ['latitude'] = $this->latitude;
		$json ['longitude'] = $this->longitude;
		$json ['created_on'] = $this->created_on;
		$json ['contact_no'] = $this->contact_no;
		if (! empty ( $this->profile_file )) {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'user/download/',
					'profile_file' => $this->profile_file 
			] );
		} else {
			$json ['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'themes/green/img/user.jpeg' 
			] );
		}
		$json ['role_id'] = $this->role_id;
		if ($this->getWorkerSkillDetail ())
			$json ['worker_skills'] = $this->getWorkerSkillDetail ();
		else
			$json ['worker_skills'] = [ ];
        $json ['review_count'] = intval($this->getWorkerReviewCount ());
        if ($this->getWorkerReviews ())
			$json ['reviews'] = $this->getWorkerReviews ();
		else
			$json ['reviews'] = [ ];
		if ($this->getWorkerAvgRating ())
			$json ['overall_avg'] = floatval($this->getWorkerAvgRating ());
		else
			$json ['overall_avg'] = 0.0;
        if (! empty ( $this->getWorkerPortfolios())) {
            $json ['portfolio_detail'] = $this->getWorkerPortfolios ();
        } else {
            $json ['portfolio_detail'] = [];
        }
		return $json;
	}
	
	public function getAvgRating() {
		$model = Rating::find ()->where ( [ 
				'user_id' => $this->id 
		] )->average ( 'rate' );
		return $model;
	}

	public function getWorkerAvgRating() {
		$model = Rating::find ()->where ( [
				'user_id' => $this->id ,
                'type_id' => Rating::TYPE_WORKER
		] )->average ( 'rate' );

		if($model != null)
			return $model;
		else
			return '0';
	}

	public function getCustomerAvgRating() {
		$model = Rating::find ()->where( [
				'user_id' => $this->id,
				'type_id' => Rating::TYPE_CUSTOMER
		])->average( 'rate' );

		if($model != null)
			return $model;
		else
			return '0';
	}

    public function getReviewCount() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->id
        ])->count();
        if ($model) {
            return $model;
        }else{
            return '0';
        }
    }
    
    public function getWorkerReviewCount() {
        $model = Rating::find ()->where ( [
            'user_id' => $this->id,
            'type_id' => Rating::TYPE_WORKER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return '0';
        }
    }

    public function getCustomerReviewCount() {
		$model = Rating::find ()->where ( [
            'user_id' => $this->id,
            'type_id' => Rating::TYPE_CUSTOMER
        ])->count();
        if ($model) {
            return $model;
        }else{
            return '0';
        }
    }

	public function getReviews() {
		$model = Rating::find ()->where ( [ 
				'user_id' => $this->id
		] )->all ();
		
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $m ) {
				$list [] = $m->asJson ();
			}
			if (! empty ( $list )) {
				return $list;
			}
		}
	}
	public function getWorkerReviews() {
		$model = Rating::find ()->where ( [
				'user_id' => $this->id,
                'type_id' => Rating::TYPE_WORKER
		] )->all ();

		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $m ) {
				$list [] = $m->asJson ();
			}
			if (! empty ( $list )) {
				return $list;
			}
		}
	}
    public function getWorkerPortfolios() {
        $model = UserPortfolio::find ()->where ( [
            'created_by_id' => $this->id,
        ] )->all ();

        if (! empty ( $model )) {
            $list = [ ];
            foreach ( $model as $m ) {
                $list [] = $m->asJson ();
            }
            if (! empty ( $list )) {
                return $list;
            }
        }
    }
	public function getWorkerSkillDetail() {
		$model = WorkerSkill::find ()->where ( [ 
				'created_by_id' => $this->id 
		] )->all ();
		
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $m ) {
				$list [] = $m->asJson ();
			}
			if (! empty ( $list )) {
				return $list;
			}
		}
	}

    /**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		return static::findOne ( [ 
				'id' => $id,
				'state_id' => self::STATE_ACTIVE 
		] );
	}
	
	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		echo "findIdentityByAccessToken";
		return static::findOne ( [ 
				'activation_key' => $token,
				'state_id' => self::STATE_ACTIVE 
		] );
	}
	
	/**
	 * Finds user by email
	 *
	 * @param string $email        	
	 * @return static|null
	 */
	public static function getLocation($address, $region, $id) {
		
		// $address = $this->address;
//		$address = str_replace ( " ", "+", $address );
//		// $region = $this->country;
//		$list = [ ];
//
//		$json = file_get_contents ( "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region" );
//
//		$json = json_decode ( $json );
//
//		$list [0] = $json->{'results'} [0]->{'geometry'}->{'location'}->{'lat'};
//		$list [1] = $json->{'results'} [0]->{'geometry'}->{'location'}->{'lng'};
		/*
		 * if(!empty($list))
		 * {
		 * return $list;
		 * }
		 */
		$user = User::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		if (! empty ( $user )) {
			$user->latitude =  "37.76";//$list [0];
			$user->longitude = "-122.22";//$list [1];
			if (! $user->save ()) {
				return false;
			}
		}
		return true;
	}
	public static function findByUsername($username) {
		return static::findOne ( [ 
				'email' => $username 
		] );
	}

	public function getLoginUrl() {
		return Yii::$app->urlManager->createAbsoluteUrl ( [ 
				'user/login' 
		] );
	}
	public static function randomPassword($count = 8) {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
		$pass = [ ];
		$alphaLength = strlen ( $alphabet ) - 1;
		for($i = 0; $i < $count; $i ++) {
			$n = rand ( 0, $alphaLength );
			$pass [] = $alphabet [$n];
		}
		return implode ( $pass );
	}
	
	/**
	 * Finds user by password reset token
	 *
	 * @param string $token
	 *        	password reset token
	 * @return static|null
	 */
	public function getBankDetail() {
		$model = Bank::find ()->where ( [ 
				'created_by_id' => $this->id 
		] )->one ();
		
		if (! empty ( $model )) {
			return $model->asJson ();
		}
	}
	public function getUserProfileDetail() {
		$model = UserProfile::find ()->where ( [ 
				'created_by_id' => $this->id 
		] )->one ();
		if (! empty ( $model )) {
			return $model->asJson ();
		}
	}
	public static function findByPasswordResetToken($token) {
		if (! static::isPasswordResetTokenValid ( $token )) {
			return null;
		}
		return static::findOne ( [ 
				'activation_key' => $token,
				'state_id' => self::STATE_ACTIVE 
		] );
	}
	
	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token
	 *        	password reset token
	 * @return boolean
	 */
	public function getResetUrl() {
		return Yii::$app->urlManager->createAbsoluteUrl ( [ 
				'user/resetpassword',
				'token' => $this->activation_key 
		] );
	}

	public static function isPasswordResetTokenValid($token) {
		if (empty ( $token )) {
			return false;
		}
		$expire = 3600;
		$parts = explode ( '_', $token );
		$timestamp = ( int ) end ( $parts );
		return $timestamp + $expire >= time ();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->getPrimaryKey ();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->activation_key;
	}
	
	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return $this->getAuthKey () === $authKey;
	}
	public function hashPassword($password) {
		// if (YII_ENV == 'dev')
		return md5 ( $password );
		// return Yii::$app->security->generatePasswordHash ( yii::$app->name . $password );
	}
	
	/**
	 * Validates password
	 *
	 * @param string $password
	 *        	password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return $this->password === $this->hashPassword ( $password );
	}
	
	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password        	
	 */
	public function setPassword($password) {
		$this->password = $this->hashPassword ( $password );
	}
    public function setSocialId($id) {
        $this->social_id = $id;
    }
    public function setSocialType($type) {
        $this->social_type = $type;
    }
    /**
	 * convert normal password to hash password before saving it to database
	 */
	
	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey() {
		$this->activation_key = Yii::$app->security->generateRandomString ();
	}
	
	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken() {
		$this->activation_key = Yii::$app->security->generateRandomString () . '_' . time ();

	}
	
	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken() {
		$this->activation_key = null;
	}
	public static function isManager() {
		$user = Yii::$app->user->identity;
		if ($user == null)
			return false;
		
		return ($user->role_id == User::ROLE_MANAGER || self::isAdmin ());
	}
	public static function isAdmin() {
		$user = Yii::$app->user->identity;
		if ($user == null)
			return false;
		
		return ($user->role_id == User::ROLE_ADMIN);
	}
    public static function isSubAdmin() {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->role_id == User::ROLE_SUBADMIN);
    }
	public static function isGuest() {
		if (Yii::$app->user->isGuest) {
			return true;
		}
		return false;
	}
	public function getProfileImage() {
		$user = Yii::$app->user->identity;
		$image_path = UPLOAD_PATH . $user->profile_file;
		
		if (! isset ( $user->profile_file ) || ! file_exists ( $image_path ))
			throw new NotFoundHttpException ( Yii::t ( 'app', "File not found" ) );
		
		return \yii::$app->response->sendFile ( $image_path, $user->profile_file );
	}

	public function getWorkerCancelRate() {
		$totalJob = Job::find ()->where ( [
				'worker_id' => $this->id
		] )->count();
		$cancelJob = Job::find()->where ( [
			'worker_id' => $this->id,
			'cancel_by' => Job::CANCEL_BY_WORKER
		])->count();

		if($totalJob != 0)
			return $cancelJob / $totalJob;
		else
			return 0;
	}
	
	public function getCustomerCancelRate() {
		$totalJob = Job::find ()->where ( [
				'created_by_id' => $this->id
		] )->count();

		$cancelJob = Job::find()->where ( [
			'created_by_id' => $this->id,
			'cancel_by' => Job::CANCEL_BY_CUSTOMER
		])->count();

		if($totalJob != 0)
			return $cancelJob / $totalJob;
		else
			return '0';
	}

	// public function getCustomerCurBalance() {
	// 	$money = Transaction::find ()->where( [
	// 		'reciever_id' => $this->id,
	// 	])->sum('amount');

	// 	if($money)
	// 		return $money;
	// 	else
	// 		return 0;
	// }

	public function getWorkerCurTaskCount($status) {
		$jobCount = Job::find ()->where ( [
				'worker_id' => $this->id,
				'status' => $status
		] )->count();

		if($jobCount)
			return $jobCount;
		else
			return '0';
	}

	public function getCustomerCurTaskCount($status) {
		$jobCount = Job::find ()->where ( [
				'created_by_id' => $this->id,
				'status' => $status
		] )->count();

		if($jobCount)
			return $jobCount;
		else
			return '0';
	}


	public function getWorkerCurBalance() {
		$money = Transaction::find ()->where( [
			'reciever_id' => $this->id,
		])->sum('amount');

		if($money)
			return $money;
		else
			return '0';
	}

    public function getWorkerCurTransaction() {
        $money = Transaction::find ()->where( [
            'reciever_id' => $this->id,
        ])->sum('amount');

        if($money)
            return $money;
        else
            return 0;
    }

	public function getCustomerCurBalance() {
		$money = Transaction::find ()->where( [
			'created_by_id' => $this->id,
		])->sum('amount');

		if($money)
			return $money;
		else
			return '0';
	}



}