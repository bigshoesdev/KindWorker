<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\models\AuthSession;
use app\models\Bank;
use app\models\LoginForm;
use app\models\search\WorkerSkill;
use app\models\Slot;
use app\models\User;
use app\models\UserAddress;
use app\models\UserPortfolio;
use app\models\UserProfile;
use app\models\Job;
use app\models\Notification;
use app\modules\api\controllers\ApiTxController;
use Braintree\Configuration;
use bryglen\apnsgcm\Apns;
use bryglen\braintree\Braintree;
use yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\Transaction;
use app\models\WorkerAmount;
use tejrajs\uspsapi\USPSAddress;
use tejrajs\uspsapi\USPSAddressVerify;

/**
 * UserController implements the API actions for User model.
 */
class UserController extends ApiTxController {
	public function behaviors() {
		return ArrayHelper::merge ( parent::behaviors (), [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'ruleConfig' => [ 
								'class' => AccessRule::className () 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
												'check',
												'get',
												'update',
												'delete',
												'view',
												'add',
												'logout',
												'change-password',
												'add-log',
												'get-address',
												'add-address',
												'delete-address',
												'profile',
												'detail',
												'is-approved',
												'worker-list',
												'customer-profile-update',
												'customer-list',
												'signup',
												'slot',
												'state-list',
												'step1',
												'step2',
												'step3',
												'step4',
												'add-card',
                                                'pay',
                                                'view-skill',
												'usps-api',
												'add-worker-skill',
												'last-search-address',
												'address-update',
                                                'add-portfolio',
                                                'delete-portfolio',
                                                'dashboard-worker',
                                                'dashboard-customer',
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [
												'login',
												'signup',
                                                'update',
                                                'social',
                                                'recover',
												'check',
												'mode',
												'beat',
												'get',
                                                'forgot-password',
                                                'instagram',
												'add-log',
												'pay',
												'usps-api',
												'last-search-address',
												'address-update' 
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'*' 
										] 
								] 
						] 
				] 
		] )
		// '@'
		
		;
	}

	public function actionUspsApi() {
		$verify = new USPSAddressVerify ( 'xxxx' );
		
		// During test mode this seems not to always work as expected
		$verify->setTestMode ( true );
		
		// Create new address object and assign the properties
		// apartently the order you assign them is important so make sure
		// to set them as the example below
		$address = new USPSAddress ();
		$address->setFirmName ( 'Apartment' );
		$address->setApt ( '100' );
		$address->setAddress ( '9200 Milliken Ave' );
		$address->setCity ( 'Rancho Cucomonga' );
		$address->setState ( 'CA' );
		$address->setZip5 ( 91730 );
		$address->setZip4 ( '' );
		
		// Add the address object to the address verify class
		$verify->addAddress ( $address );
		
		// Perform the request and return result
		print_r ( $address );
		print_r ( $verify->verify () );
		print_r ( $verify->getArrayResponse () );
		
		var_dump ( $verify->isError () );
		
		// See if it was successful
		if ($verify->isSuccess ()) {
			echo 'Done';
		} else {
			echo 'Error: ' . $verify->getErrorMessage ();
		}
	}
	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->txIndex ( "\app\models\search\User" );
	}
	
	/**
	/**
	 * Displays a single User model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		return $this->txget ( $id, "app\models\User" );
	}
	
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		return $this->txSave ( "app\models\User" );
	}
	
	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$data = [ ];
		$model = $this->findModel ( $id );
        $params = Yii::$app->request->post ();
		if ($model->load ( $params)) {

                if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model;
			} else {
				$data ['error'] = $model->flattenErrors;
			}
		} else {
			$data ['error_post'] = 'No Data Posted';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionAddressUpdate($id, $type) {
		$data = [ ];
		
		$model = $this->findModel ( $id );
		if ($model->load ( Yii::$app->request->post () )) {
			if ($type == 1) {
				$model->approval_address = $_POST ['User'] ['address'];
				$model->status = User::STATUS_NORMAL;
				$model->role_id = User::ROLE_CUSTOMER;
				$model->is_fill = User::IS_FILL;
				$model->is_notify = User::NOT_FILL;
			}
			if ($model->save ()) {
				
				$auth = AuthSession::find ()->where ( [ 
						'created_by_id' => $id 
				] )->one ();
				if (! empty ( $auth )) {
					$auth->delete ();
				}
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model;
			} else {
				$data ['error'] = $model->getErrors ();
			}
		} else {
			$data ['error_post'] = 'No Data Posted';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionCheck() {
		$data = [ ];
		if (! \Yii::$app->user->isGuest) {
			$user = \Yii::$app->user->identity;
			$data ['status'] = self::API_OK;
			$data ['detail'] = $user->asJson ();
		} else {
			$headers = AuthSession::getHead ();
			$auth_code = isset ( $headers ['auth_code'] ) ? $headers ['auth_code'] : null;
			if ($auth_code == null)
				$auth_code = \Yii::$app->request->getQueryString ( 'auth_code' );
			if ($auth_code) {
				$auth_session = AuthSession::find ()->where ( [ 
						'auth_code' => $auth_code 
				] )->one ();
				if ($auth_session) {
					$data ['status'] = self::API_OK;
					if (isset ( $_POST ['AuthSession'] )) {
						$auth_session->device_token = $_POST ['AuthSession'] ['device_token'];
						if ($auth_session->save ()) {
							$data ['auth_session'] = 'Auth Session updated';
						} else {
							
							$data ['error'] = $auth_session->flattenErrors;
						}
					}
				} else
					$data ['error'] = 'session not found';
			} else {
				$data ['error'] = 'Auth code not found';
				$data ['auth'] = isset ( $auth_code ) ? $auth_code : '';
			}
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionSignup() {
		$data = [ ];
		$model = new User ();
        if ($model->load ( Yii::$app->request->post () )) {
			$email_identify = User::findByUsername ( $model->email );
			if (empty ( $email_identify )) {
				$model->setPassword ( $model->password );
				$model->state_id = User::STATE_ACTIVE;
				$model->role_id = User::ROLE_CUSTOMER;
                ///TESTCNS
//                $braintree = Yii::$app->braintree;
//                $response = $braintree->call('Customer', 'create',[
//                    'firstName' => $model->first_name,
//                    'lastName' => $model->last_name,
//                    'email' => $model->email,
//                    'phone' => $model->contact_no
//                ]);
                if(true){  ///$response->success){
                    $model->braintree_id =  "asdfghijkl"; ///$response->customer->id;
                ///TESTCNS
                    if ($model->save ()) {
                        \yii::$app->user->login ( $model );
                        $loginform = new LoginForm ();
                        $loginform->device_token = $model->device_token;
                        $loginform->device_type = $model->device_type;
                        $data ['status'] = self::API_OK;
                        $data ['auth_code'] = AuthSession::newSession ( $loginform )->auth_code;
                        $data ['detail'] = $loginform->asJson ();
                        $data ['user_detail'] = $model->asJson ();
                    } else {
                        $data ['error'] = $model->errorsString;
                    }
                }
                else{
                    $data ['error'] = "Braintree customer cannot create.";
                }

			} else {
				$data ['error'] = "Email already exists.";
			}
		}
		return $this->sendResponse ( $data );
	}

	public function actionSocial()
    {
        $data = [ ];
        $model = new User ();
        if ($model->load ( Yii::$app->request->post () )) {
            $email_identify = User::findByUsername ( $model->email );
            if (empty ( $email_identify )) {
                $model->setPassword ( $model->password );
                $model->state_id = User::STATE_ACTIVE;
                $model->role_id = User::ROLE_CUSTOMER;
                if ($model->save ()) {
                    \yii::$app->user->login ( $model );
                    $loginform = new LoginForm ();
                    $loginform->device_token = $model->device_token;
                    $loginform->device_type = $model->device_type;
                    $data ['auth_code'] = AuthSession::newSession ( $loginform )->auth_code;
                    $data ['status'] = self::API_OK;
                    $data ['detail'] = $loginform->asJson ();
                    $data ['user_detail'] = $model->asJson ();
                } else {
                    $data ['error'] = $model->errorsString;
                }
            }
            else {
                $email_identify->setSocialId($model->social_id);
                $email_identify->setSocialType($model->social_type);
                if ($email_identify->save ()) {
                    \yii::$app->user->login ( $email_identify );
                    $loginform = new LoginForm ();
                    $loginform->device_token = $model->device_token;
                    $loginform->device_type = $model->device_type;
                    $data ['auth_code'] = AuthSession::newSession ( $loginform )->auth_code;
                    $data ['status'] = self::API_OK;
                    $data ['user_detail'] = $model->asJson ();
                    $data ['detail'] = $loginform->asJson ();
                } else {
                    $data ['error'] = $model->errorsString;
                }

            }
        }
        return $this->sendResponse ( $data );
    }
	public function actionIsFill($id) {
		$data = [ ];
		$model = User::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		if (! empty ( $model )) {
			$model->is_fill = User::IS_FILL;
			$model->role_id = User::ROLE_WORKER;
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ( true );
			} else {
				$data ['error'] = $model->getErrors ();
			}
		} else {
			$data ['error'] = 'User Not Found';
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 *
	 * @return string|string[]|NULL[]
	 */
	public function actionLogin() {
		$data = [ ];
		$model = new LoginForm ();
        if ($model->load ( Yii::$app->request->post () )) {
        	$user = User::findByUsername ( $model->username );
			if ($user) {
				if ($model->login ()) {
					$data ['status'] = self::API_OK;
					$userModel = \Yii::$app->user->identity;
					$user_details = User::find ()->where ( [ 
							'id' => $userModel->id 
					] )->one ();
					if (! empty ( $user_details )) {
						$data ['status'] = self::API_OK;
						$data ['auth_code'] = AuthSession::newSession ( $model )->auth_code;
						$data ['detail'] = $model->asJson ();
						$data ['user_detail'] = $user->asJson ();
					} else {
						$data ['error'] = 'User Not Found';
					}
				} else {
					$data ['error'] = 'Incorrect Password';
				}
			} else {
				$data ['error'] = ' Incorrect Username';
			}
		} else {
			$data ['error'] = "No data posted.";
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionLogout() {
		$data = [ ];
		if (Yii::$app->user->logout ())
        {
            $data ['status'] = self::API_OK;
            $data ['message'] = 'Logout successed';
        }
		else
        {
            $data ['error'] = 'Logout failed';
        }
		return $this->sendResponse ( $data );
	}
	public function actionChangePassword() {
		$data = [ ];
		$data ['post'] = $_POST;
		$model = User::findOne ( [ 
				'id' => \Yii::$app->user->identity->id 
		] );
		
		$newModel = new User ( [ 
				'scenario' => 'changepassword' 
		] );
		if ($newModel->load ( Yii::$app->request->post () ) && $newModel->validate ()) {
			if ($model->validatePassword ( $newModel->oldPassword )) {
				$model->setPassword ( $newModel->newPassword );
				if ($model->save ()) {
					
					$data ['status'] = self::API_OK;
				} else {
					$data ['error'] = 'Incorrect Password';
				}
			} else {
				$data ['error'] = ' Old password is incorrect';
			}
		}
		return $this->sendResponse ( $data );
	}
	public function actionAddLog() {
		$data = [ ];
		$model = new Log ();
		if ($model->load ( Yii::$app->request->post () ) && $model->validate ()) {
			if ($model->save ()) {
				$email = $model->email;
				$view = 'errorlog';
				$sub = "An Error/Crash was reported : " . \Yii::$app->params ['company'];
				Yii::$app->mailer->compose ( [ 
						'html' => 'errorlog' 
				], [ 
						'user' => $model 
				] )->setTo ( \Yii::$app->params ['adminEmail'] )->setFrom ( \Yii::$app->params ['logEmail'] )->setSubject ( $sub )->send ();
			}
		}
		$data ['status'] = self::API_OK;
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionDelete($id) {
		return $this->txDelete ( $id, "User" );
	}
	public function actionDetail() {
		$data = [ ];
		$model = User::find ()->where ( [ 
				'id' => \yii::$app->user->id 
		] )->one ();
		if (! empty ( $model )) {
			$data ['status'] = self::API_OK;
			$data ['detail'] = $model->asWorkerDetailJson ( true );
		} else {
			$data ['error'] = 'User Not Found';
		}
		return $this->sendResponse ( $data );
	}
	public function actionAddPortfolio() {
		$data = [ ];
		$portfolio = new UserPortfolio();
		if (isset ($_FILES) ) {
			$file = UploadedFile::getInstance ( $portfolio, 'image_file' );
			if (! empty ( $file )) {
                $filename = 'portfolio_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file->extension;
                $file->saveAs ( UPLOAD_PATH . $filename);
                $portfolio->image_file = $filename;
			}
			if ($portfolio->save ()) {
                $data ['status'] = self::API_OK;
                $data ['detail'] = $portfolio->asJson();
            } else {
				$data ['error'] = $portfolio->getErrorsString ();
			}
		} else {
			$data ['error'] = 'No data Posted';
		}
		return $this->sendResponse ( $data );
	}

    public function actionDeletePortfolio($id) {
        $data = [ ];
        $portfolio = UserPortfolio::find()->where([
            'id'=>$id
        ])->one();
        if(!empty($portfolio)){
            $filePath = UPLOAD_PATH . $portfolio->image_file;
            if(file_exists($filePath))
                unlink($filePath);
            $portfolio->delete();
            $data['status'] = self::API_OK;
            $data['message'] = 'Portfolio delete is succeded.';
        }else{
            $data['error'] = 'Portfolio delete is failed.';
        }
        return $this->sendResponse ( $data );
    }

	public function actionCustomerProfileUpdate() {
		$data = [ ];
		$requestData = Yii::$app->request->post ();
		$error = [ ];
		$user = \yii::$app->user->identity;
		$user->scenario = "update_customer";
		
		if (($user->load ( $requestData ))) {
			
			$image = UploadedFile::getInstance ( $user, 'profile_file' );
			$filePath = '';
			if (! empty ( $image )) {
                $filename = 'customer_profile_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $image->extension;
                $image->saveAs ( UPLOAD_PATH . $filename );
                $filePath = UPLOAD_PATH . $user->profile_file;
                $user->profile_file = $filename;
			}
			if ($user->save ()) {
//			    if(file_exists($filePath))
//			        unlink($filePath);
				$data ['status'] = self::API_OK;
				$data ['detail'] = $user->asJson ();
			} else {
				$data ['error'] = $user->getErrorsString ();
			}
		} else {
			$data ['error'] = 'No data Posted';
		}
		return $this->sendResponse ( $data );
	}
	public function actionWorkerList() {
		$data = [ ];
		$query = User::find ()->where ( [ 
				'role_id' => User::ROLE_WORKER 
		] );
		
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '20' 
				] 
		] );
		// 'page' => $page
		
		$data ['pageSize'] = $dataProvider->pagination->pageSize;
		if (count ( $dataProvider->models ) > 0) {
			
			foreach ( $dataProvider->models as $mod ) {
				
				$list [] = $mod->asJson ();
			}
			$data ['pageCount'] = isset ( $dataProvider->pagination ) ? $dataProvider->pagination->pageCount : '0';
			$data ['status'] = self::API_OK;
			$data ['list'] = $list;
			$data ['data'] = $data;
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionCustomerList() {
		$data = [ ];
		$model = User::find ()->where ( [ 
				
				'role_id' => User::ROLE_CUSTOMER 
		] );
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '20' 
				] 
		] );
		$data ['pageSize'] = $dataProvider->pagination->pageSize;
		if (count ( $dataProvider->models ) > 0) {
			
			foreach ( $dataProvider->models as $mod ) {
				
				$list [] = $mod->asJson ();
			}
			$data ['pageCount'] = isset ( $dataProvider->paginaton ) ? $dataProvider->pagination->pageCount : '0';
			$data ['status'] = self::API_OK;
			$data ['list'] = $list;
			$data ['data'] = $data;
		}
		
		return $this->sendResponce ( $data );
	}

	public function actionStep1() {
		$data = [ ];
		$params = \yii::$app->request->post ();

        $user = User::find ()->where ( [
				'id' => \yii::$app->user->id 
		] )->one ();
		
		// $user->scenario = 'step1';
		
		if (! empty ( $user )) {
			if ($user->load ( $params )) {
                $user->step = User::STEP_ONE;
                if ($user->getLocation($user->address, $user->country, $user->id)) {
                    if ($user->save()) {
                        $data ['status'] = self::API_OK;
                        $data ['message'] = 'User Information has been Added Successfully';
                        $data ['detail'] = $user;
                    } else {
                        $data ['error'] = $user->getErrorsString();
                    }
                }
            }
		} else {
			$data ['error'] = 'No User Found';
		}
		
		return $this->sendResponse ( $data );
	}
	public function actionStep2() {
		$data = [ ];
        $params = \yii::$app->request->post ();
		$user = User::find ()->where ( [ 
				'id' => \yii::$app->user->id 
		] )->one ();
		if (! empty ( $user )) {
			$data = isset ( $params ['WorkerSkill'] ) ? $params ['WorkerSkill'] : [ ];
			$data = json_decode ( $data, true );
			if (! empty ( $data )) {
				foreach ( $data as $materials ) {
					if (! empty ( $materials )) {
						$materialModel = new WorkerSkill ();
						$materialModel->hourly_rate = $materials ['hourly_rate'];
						$materialModel->sub_category_id = $materials ['sub_category_id'];
						$materialModel->category_id = $materials ['category_id'];
						$materialModel->type_id = $materials ['type_id'];
						$materialModel->experience = $materials ['experience'];
						$materialModel->delivery_time = $materials ['delivery_time'];
						$materialModel->description = $materials ['description'];
						$materialModel->travel_me = isset($materials ['travel_me'])?intval($materials ['travel_me']):0;
						$materialModel->travel_customer = isset($materials ['travel_customer'])?intval($materials ['travel_customer']):0;
						$materialModel->created_by_id = $user->id;
                        $materialModel->rate_type_id = $materials ['rate_type_id'];
						$materialModel->state_id = WorkerSkill::STATE_ACTIVE;
						if (!$materialModel->save ()) {
                            $data ['error'] = $materialModel->getErrorsString ();
                            return $this->sendResponse ( $data );
						}
					} else {
						$data ['error'] = 'Worker Skills detail not formated.';
                        return $this->sendResponse ( $data );
					}
				}
				if($this->saveSlot())
                {
                    $user->step = User::STEP_TWO;
                    $user->vacation_mode = User::VACATION_OFF;
                    $user->save ();
                    $data ['status'] = self::API_OK;
                    $data ['message'] = 'Worker Skills has been Added Successfully';
                    $data ['detail'] = $user->asJson ();
                } else{
                    $data ['error'] = 'Slot save fail';
                }
			}
		} else {
			$data ['error'] = 'No User Found';
		}
		
		return $this->sendResponse ( $data );
	}

	public function actionStep3() {
		$data = [ ];
		$requestData = Yii::$app->request->post ();

        $user = \yii::$app->user->identity;

		$profile = UserProfile::find ()->where ( [
				'created_by_id' => $user->id
		] )->one ();

		if (empty ( $profile )) {
			$profile = new UserProfile ();
		}

		if (isset ($_FILES)) {
            $file = UploadedFile::getInstance($user, 'profile_file');
            if (!empty ($file)) {
                $filename = 'profile_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file->extension;
                $file->saveAs(UPLOAD_PATH . $filename);
                $filePath = UPLOAD_PATH . $user->profile_file;
                $user->profile_file = $filename;
                $user->step = User::STEP_THREE;
                if ($user->save()) {
                    $document_file = UploadedFile::getInstance($profile, 'document_file');
                    if (!empty ($document_file)) {
                        $filename = 'document_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $document_file->extension;
                        $document_file->saveAs(UPLOAD_PATH . $filename);
                        $filePath = UPLOAD_PATH . $profile->document_file;
                        $profile->document_file = $filename;
                        if ($profile->save()) {
                            $data ['status'] = self::API_OK;
                            $data ['detail'] = $user->asJson(true);
                        } else {
                            $data ['error'] = $profile->getErrorsString();
                        }
                    } else {
                        $data ['error'] = 'DocumentFile Save Failed';
                    }
                } else {
                    $data ['error'] = $user->getErrorsString();
                }
            } else {
                $data ['error'] = 'Profile Save Failed';
            }
        }
        else {
            $data ['error'] = 'RequestData Load Failed';
        }

		return $this->sendResponse ( $data );
	}

	public function saveSlot(){
        $flag = false;
        $alreadyExist = Slot::find()->where([
            'created_by_id' => \yii::$app->user->id
        ])->all();
        if (! empty ( $alreadyExist )) {
            foreach ( $alreadyExist as $del ) {
                if ($del->delete ()) {
                    $flag = true;
                } else {
                    $flag = false;
                    break;
                }
            }
        } else {
            $flag = true;
        }
        if($flag==true){
            for($day_id = 0;$day_id<7;$day_id++){
                $slotnew = new Slot();
                $slotnew->times = '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0';
                $slotnew->day_id = $day_id;
                $slotnew->type_id = Slot::TYPE_FREE;
                if(!$slotnew->save()){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
	public function actionStep4() {
		$data = [ ];
		$requestData = Yii::$app->request->post ();
		$error = [ ];
		$user = \yii::$app->user->identity;
		$user->scenario = "step4";
		$bankModel = Bank::find ()->where ( [ 
				'created_by_id' => $user->id 
		] )->one ();
		if (empty ( $bankModel )) {
			$bankModel = new Bank ();
			$bankModel->created_by_id = $user->id;
		}
		
		$user->step = User::STEP_FOUR;
		if ($bankModel->load ( $requestData )) {
			
			if (($user->save () && $bankModel->save ())) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $user->asJson ( true );
			} else {
				$data ['error'] = $user->getErrorsString ();
			}
		} else {
			$data ['error'] = 'No data Posted';
		}
		return $this->sendResponse ( $data );
	}
	public function actionPay() {

		$data = [ ];
        $user = \yii::$app->user->identity;
        \Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
        $acct = \Stripe\Account::create(array(
            "country" => "US",
            "type" => "custom",
            "email"=> $user->email
        ));
        $user->stripe_customer_id = $acct->id;

        if($user->save()){
            $data['status'] = self::API_OK;
            $data['message'] = 'Stripe Account created successfully.';
        } else{
            $data['error'] = 'Stripe Account cannot create.';
        }

//        $braintree = Yii::$app->braintree;
//        $response = $braintree->call('Customer', 'update','15552090',[
//
//            'firstName' => 'test-1545',
//            'lastName' => 'asdf',
//            'company' => 'New Company',
//            'email' => 'new.email@example.com',
//            'phone' => 'new phone',
//            'fax' => 'new fax',
//            'website' => 'http://new.example.com'
//
//        ]);

        return $this->sendResponse ( $data );
	}
	public function actionAddCard()
    {
        $data = [ ];
        \Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
        $user = User::find ()->where ( [
            'role_id' => User::ROLE_ADMIN
        ] )->one ();
        $model = \Yii::$app->user->identity;
        $worked_amount = WorkerAmount::find ()->one ();

        $params = \Yii::$app->request->bodyParams;
        if (! empty ( $worked_amount )) {
            if ($params) {
                try {
//                    $stripe_charge = \Stripe\Charge::create ( array (
//                        "amount" => $worked_amount->amount * 100,
//                        "description" => "Payment Done",
//                        "source" => $params ['User'] ['token'],
//                        "currency" => "USD"
//                    ) );

                    if (true){//$stripe_charge->status == "succeeded") {
                        $transaction = new Transaction ();
                        $transaction->amount = $worked_amount->amount;
                        $transaction->currency = 'USD';
                        $transaction->transaction_num = "111111";//$stripe_charge->balance_transaction;
                        $transaction->charge_id = "222222";//$stripe_charge->id;
                        $transaction->created_by_id = $user->id;
                        $transaction->model_type = get_class ( $model );
                        $transaction->reciever_id = $user->id;
                        $model->security_check_no = isset ( $params ['User'] ['security_check_no'] ) ? $params ['User'] ['security_check_no'] : '';
                        $model->is_service = isset ( $params ['User'] ['is_service'] ) ? $params ['User'] ['is_service'] : '';
                        if ($transaction->save ()) {
                            $model->step = User::STEP_FIVE;
                            $model->role_id = User::ROLE_WORKER;
                            $model->is_fill = User::IS_FILL;
                            if ($model->save ()) {
                                $data ['status'] = self::API_OK;
                                $data ['msg'] = "Payment is received";
                                $data ['detail'] = $model->asJson ( true );
                            } else {
                                $data ['error'] = $models->getErrorsString ();
                            }
                        } else {
                            $data ['error'] = $transaction->getErrorsString ();
                        }
                    }
                } catch ( \Exception $e ) {
                    $data ['error'] = $e->getMessage ();
                }
            } else {
                $data ['error'] = 'No Data posted';
            }
        } else {
            $data ['error'] = 'Worker signup amount not set.Please Contact Admin.';
        }
        return $this->sendResponse ( $data );
	}


	public function actionViewSkill() {
		$data = [ ];
		$models = \app\models\WorkerSkill::find ()->where ( [ 
				'created_by_id' => \yii::$app->user->id 
		] )->groupBy ( 'category_id' )->all ();
		if (! empty ( $models )) {
			$list = [ ];
			foreach ( $models as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $list;
			}
		} else {
			$data ['error'] = 'Nothing Found';
		}
		return $this->sendResponse ( $data );
	}
	public function actionAddWorkerSkill() {
		$data = [ ];
		
		$params = \yii::$app->request->bodyparams;
		
		$model = \app\models\WorkerSkill::find ()->where ( [ 
				'category_id' => $params ['WorkerSkill'] ['category_id'],
				'sub_category_id' => $params ['WorkerSkill'] ['sub_category_id'],
				'created_by_id' => \yii::$app->user->id 
		] )->one ();
		
		if (empty ( $model )) {
			$model = new \app\models\WorkerSkill ();
		}
		if ($model->load ( $params )) {
			isset ( $_POST ['WorkerSkill'] ['experience'] ) ? $_POST ['WorkerSkill'] ['experience'] : '0';
			$model->state_id = \app\models\WorkerSkill::STATE_ACTIVE;
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ();
			}
		} else {
			$data ['error'] = 'No Data Posted';
			}
		
		return $this->sendResponse ( $data );
	}
	
	public function actionLastSearchAddress() {
		$data = [ ];
		$model = User::find ()->where ( [
				'id' => \yii::$app->user->id
		] )->one ();
		if (! empty ( $model )) {
			$data ['status'] = self::API_OK;
			$data ['detail'] = $model->asAddress ( true );
		} else {
			$data ['error'] = 'User Not Found';
		}
		return $this->sendResponse ( $data );
	}

	public function actionGetAddress() {
        $data = [ ];
        $models = UserAddress::find ()->where ( [
            'created_by_id' => \yii::$app->user->id
        ] )->all ();
        if (! empty ( $models )) {
            $list = [ ];
            foreach ( $models as $mod ) {
                $list [] = $mod->asJson ();
            }
            if (! empty ( $list )) {
                $data ['status'] = self::API_OK;
                $data ['detail'] = $list;
            }
        } else {
            $data ['error'] = 'Nothing Found';
        }
        return $this->sendResponse ( $data );
    }

    public function actionAddAddress() {
        $data = [ ];
        $userid = \yii::$app->user->id;
        $params = \yii::$app->request->bodyparams;
        $model = UserAddress::find ()->where ( [
            'created_by_id' => $userid,
            'type' => $params['type'],
            'address' => $params['address'],
            'country' => $params['country'],
            'state' => $params['state'],
            'city' => $params['city'],
            'zipcode' => $params['zipcode']
        ] )->one ();
        if($model==null)
        {
            $model = new UserAddress();
            $model->address = $params['address'];
            $model->type = $params['type'];
            $model->country = $params['country'];
            $model->state = $params['state'];
            $model->city = $params['city'];
            $model->zipcode = $params['zipcode'];
            $model->latitude = $params['latitude'];
            $model->longitude = $params['longitude'];
            $model->created_by_id = $userid;
        }else
        {
            $model->latitude = $params['latitude'];
            $model->longitude = $params['longitude'];
            $model->created_by_id = $userid;
        }
        if ($model->save()) {
            $data ['status'] = self::API_OK;
            $data ['detail'] = 'Address add successed';
        } else {
            $data ['error'] = $model->errorsString;
        }
        return $this->sendResponse ( $data );
    }

    public function actionDeleteAddress($id) {
        $data = [ ];
        $model = UserAddress::find()->where([
            'id' => $id
        ])->one();
        if(!empty($model))
        {
            if($model->delete())
            {
                $data ['result'] = 'Address delete successed!';
                $data ['status'] = self::API_OK;
            }
            else{
                $data ['error'] = $model->errorsString;
            }
        }
        return $this->sendResponse ( $data );
    }

    public function actionForgotPassword() {
	    $data = [];
        $model = new User ();
        $model->scenario = "recover";
        $params = \yii::$app->request->bodyparams;
        if (isset ( $params )) {
            $email = trim ( $params ['User'] ['email'] );
            $user = User::findOne ( [
                'email' => $email
            ] );
            if ($user) {
                $user->generatePasswordResetToken ();
                $user->save ();
                $email = $user->email;
                $view = "passwordResetToken";
                $sub = "Recover Your Account at: " . \Yii::$app->params ['company'];

                Yii::$app->mailer->compose ( [
                    'html' => 'passwordResetToken'
                ], [
                    'user' => $user
                ] )->setTo ( $email )->setFrom ( \Yii::$app->params ['adminEmail'] )->setSubject ( $sub )->send ();
                $data['status'] = self::API_OK;
                $data['message'] =  'Please check your email to reset your password.' ;
            } else {
                $data['error'] = 'Email is not registered.';
            }
        } else{
            $data['error'] = 'No data posted.';
        }

        return $this->sendResponse ( $data );
    }

    public function actionDashboardWorker()
    {
	 	$data = [ ];
		$model = User::find ()->where ( [
				'id' => \yii::$app->user->id
		] )->one ();
		if (! empty ( $model )) {
            $data ['status'] = self::API_OK;
            $data ['data'] = array();
            $data ['data']['rating'] = $model->getWorkerAvgRating();
            $data ['data']['reviews'] = $model->getWorkerReviewCount();
            $data ['data']['taskCount'] = $model->getWorkerCurTaskCount( Job::STATE_IN_PROGRESS);
            $data ['data']['balance'] = $model->getWorkerCurBalance();
            $data ['data']['cancelRate'] = $model->getWorkerCancelRate() * 100;
            $data ['data']['transaction'] = $model->getWorkerCurTransaction();
		} else {
			$data ['error'] = 'Database error';
		}
		return $this->sendResponse ( $data );
    }

    public function actionDashboardCustomer()
    {
	 	$data = [ ];
		$model = User::find ()->where ( [
				'id' => yii::$app->user->id
		] )->one ();
		if (! empty ( $model )) {
            $data ['status'] = self::API_OK;
            $data ['data'] = array();
            $data ['data']['rating'] = $model->getCustomerAvgRating();
            $data ['data']['reviews'] = $model->getCustomerReviewCount();
            $data ['data']['taskCount'] = $model->getCustomerCurTaskCount( Job::STATE_IN_PROGRESS);
            $data ['data']['balance'] = $model->getCustomerCurBalance();
            $data ['data']['cancelRate'] = $model->getCustomerCancelRate() * 100;
		} else {
			$data ['error'] = 'Database error';
		}
		return $this->sendResponse ( $data );
    }
}
