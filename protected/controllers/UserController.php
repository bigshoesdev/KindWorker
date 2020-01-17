<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\components\ToggleAction;
use app\models\Bank;
use app\models\LoginForm;
use app\models\search\User as UserSearch;
use app\models\User;
use app\models\UserProfile;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\models\search\WorkerSkill;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends TController {
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'ruleConfig' => [ 
								'class' => AccessRule::className () 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
												'add',
												'view',
												'editabledemo',
												'update',
												'delete',
												'worker-delete',
												'customer-delete',
												'logout',
												'changepassword',
												'resetpassword',
												'profileImage',
												'toggle',
												'download',
												'approval-index',
												'banned-index',
												'deny-index',
												'approval-view',
												'flag-index',
												'toast-notification',
												'clear',
												'dashboard',
												'recover',
												'add-admin',
												'image-manager',
												'image-upload',
												'login',
												'customer-index',
												'worker-index',
												'subadmin-index',
												'request-index',
												'update-approval'
										
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												'index',
												'add',
												'shadow',
												'view',
												'editabledemo',
												'update',
												'delete',
												'logout',
												'changepassword',
												'resetpassword',
												'dashboard',
												'profileImage',
												'toggle',
												'download',
												'clear',
												'dashboard',
												'recover',
												'add-admin',
												'image-manager',
												'image-upload',
												'login',
												'update-approval' ,
												'mass',
										
										],
										'allow' => true,
										'matchCallback' => function () {
											return User::isAdmin ();
										} 
								],
								
								[ 
										'actions' => [ 
												'login',
												// 'signup',
												'recover',
												'resetpassword',
												// 'dashboard',
												'profileImage',
												'download',
												'add-admin',
                                                'add',
												'test' 
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'*' 
										] 
								] 
						] 
				],
			/* 	'verbs' => [ 
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				]  */
		];
	}
	public function actionToastNotification() {
		\Yii::$app->response->format = 'json';
		$model = User::find ()->where ( [ 
				'is_fill' => User::IS_FILL,
				'is_notify' => User::NOT_FILL 
		] )->all ();
		$response = [ ];
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $data ) {
				$list [] = [ 
						'id' => $data->id,
						'user' => $data->asJson () 
				
				];
			}
			$response ['list'] = $list;
			$response ['status'] = 'OK';
		}
		
		return $response;
	}
	public function actionFlagIndex($id) {
		\Yii::$app->response->format = 'json';
		$model = User::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		$response = [ ];
		if (! empty ( $model )) {
			$model->is_notify = User::IS_FILL;
			if ($model->save ()) {
				return $this->redirect ( [ 
						'update-approval',
						'id' => $model->id 
				] );
			}
		}
		
		return $response;
	}
	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function onAuthSuccess($client) {
		(new AuthHandler ( $client ))->handle ();
	}
	public function actions() {
		return [ 
				'toggle' => [ 
						'class' => ToggleAction::className (),
						'modelClass' => 'app\models\search\UserSearch',
						
						'setFlash' => true 
				],
				'auth' => [ 
						'class' => 'yii\authclient\AuthAction',
						'successCallback' => [ 
								$this,
								'onAuthSuccess' 
						] 
				] 
		];
	}
	public function actionTest() {
		$model = new User ();
		$model->getLocation ( $address = 'Ocean Drive, Miami Beach, FL 33139', $region = 'United States of America' );
	}
	public function actionClear() {
		$runtime = Yii::getAlias ( '@runtime' );
		$this->cleanRuntimeDir ( $runtime );
		
		$this->cleanAssetsDir ();
		return $this->goBack ();
	}
	public function actionIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionRequestIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->RequestSearch ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'request-index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionApprovalIndex() {

		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->approvalSearch ( Yii::$app->request->queryParams, User::STATUS_APPROVED );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionBannedIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->approvalSearch ( Yii::$app->request->queryParams, User::STATUS_BANNED );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionDenyIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->approvalSearch ( Yii::$app->request->queryParams, User::STATUS_DENY );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionCustomerIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->searchCustomer ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		
		return $this->render ( 'customer-index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionWorkerIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->searchWorker ( Yii::$app->request->queryParams );
		$dataProvider->pagination->pageSize = 20;
		$this->updateMenuItems ();
		return $this->render ( 'worker-index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
    public function actionSubadminIndex() {
        $searchModel = new UserSearch ();
        $dataProvider = $searchModel->searchSubAdmin ( Yii::$app->request->queryParams );
        $dataProvider->pagination->pageSize = 20;
        $this->updateMenuItems ();
        return $this->render ( 'subadmin-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ] );
    }
	public function actionAddAdmin() {
		$this->layout = "guest-main";
		$count = User::find ()->count ();
		if ($count == 0) {
			$model = new User ();
			$model->scenario = 'signup';
			if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
				$model->scenario = 'signup';
				Yii::$app->response->format = Response::FORMAT_JSON;
				return TActiveForm::validate ( $model );
			}
			if ($model->load ( Yii::$app->request->post () )) {
				$model->role_id = User::ROLE_ADMIN;
				$model->state_id = User::STATE_ACTIVE;
				if ($model->validate ()) {
					$model->setPassword ( $model->password );
					$model->generatePasswordResetToken ();
					if ($model->save ()) {
						return $this->redirect ( [ 
								'login' 
						] );
					}
				}
			}
			return $this->render ( 'signup', [ 
					'model' => $model 
			] );
		} else {
			return $this->redirect ( [ 
					'/' 
			] );
		}
	}
	
	/**
	 * Displays a single User model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel ( $id );
		$profileModel = UserProfile::find ()->where ( [ 
				'created_by_id' => $id 
		] )->one ();
		$bankModel = Bank::find ()->where ( [ 
				'created_by_id' => $id 
		] )->one ();
        $searchModel = new WorkerSkill();
		$dataProvider = $searchModel->searchView ( Yii::$app->request->queryParams );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			$this->updateMenuItems ( $model );
			
			if (! empty ( $profileModel ) && ! empty ( $bankModel )) {
				return $this->render ( 'profileview', [ 
						'model' => $model,
						'promodel' => $profileModel,
						'bankmodel' => $bankModel,
						'searchModel' => $searchModel,
						'dataProvider' => $dataProvider 
				] );
			} else {
				return $this->render ( 'view', [ 
						'model' => $model 
				] );
			}
		}
	}
	public function actionApprovalView($id) {
		$model = $this->findModel ( $id );
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'approval-view',
					'id' => $model->id 
			] );
		} else {
			$this->updateMenuItems ( $model );
			return $this->render ( 'approval-view', [ 
					'model' => $model 
			] );
		}
	}
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionAdd() {
		$this->layout = 'main';
		$model = new User ();
		$model->role_id = User::ROLE_SUBADMIN;
		$model->state_id = User::STATE_ACTIVE;
		$model->scenario = 'add';
		if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
			$model->scenario = 'add';
			Yii::$app->response->format = Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( Yii::$app->request->post () )) {
			$model->role_id = User::ROLE_SUBADMIN;
			$model->state_id = User::STATE_ACTIVE;
			$image = UploadedFile::getInstance ( $model, 'profile_file' );
			if (! empty ( $image )) {
                $filename = 'profile_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $image->extension;
                $image->saveAs ( UPLOAD_PATH . $filename );
				$model->profile_file = $filename;
				
				Yii::$app->getSession ()->setFlash ( 'success', 'User Added Successfully.' );
			}
			if ($model->validate ()) {
				$model->generatePasswordResetToken ();
				$model->setPassword ( $model->addPassword );
				if ($model->save ()) {
					$email = $model->email;
					
					$view = 'sendPassword';
					$sub = "Welcome! You new account is ready" . \Yii::$app->params ['company'];
					
					Yii::$app->mailer->compose ( [ 
							'html' => 'sendPassword' 
					], [ 
							'user' => $model 
					] )->setTo ( $email )->setFrom ( \Yii::$app->params ['adminEmail'] )->setSubject ( $sub )->send ();
					
					Yii::$app->getSession ()->setFlash ( 'success', ' User Added Successfully.' );
					return $this->redirect ( [ 
							'/dashboard/index',
							'id' => $model->id 
					] );
				}
			}
		}
		$this->updateMenuItems ();
		return $this->render ( 'add', [
				'model' => $model 
		] );
	}
	public function actionRecover() {
		$this->layout = 'guest-main';
		$model = new User ();
		$model->scenario = "recover";
		if (isset ( $_POST ['User'] )) {
			$email = trim ( $_POST ['User'] ['email'] );
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
				
				\Yii::$app->session->setFlash ( 'success', 'Please check your email to reset your password. ' );
			} else {
				
				\Yii::$app->session->setFlash ( 'error', 'Email is not registered.' );
			}
		}
		return $this->render ( 'requestPasswordResetToken', [ 
				'model' => $model 
		] );
	}
	
	public function actionResetpassword($token) {
		$this->layout = 'guest-main';
		
		$model = User::findByPasswordResetToken ( $token );
		if (! ($model)) {
			\Yii::$app->session->setFlash ( 'error', 'This URL is expired.' );
		}
		$newModel = new User ( [
				'scenario' => 'resetpassword'
		] );
		if (Yii::$app->request->isAjax && $newModel->load ( Yii::$app->request->post () )) {
			
			Yii::$app->response->format = Response::FORMAT_JSON;
			return TActiveForm::validate ( $newModel );
		}
		if ($newModel->load ( Yii::$app->request->post () ) && $newModel->validate ()) {
			$model->setPassword ( $newModel->password );
			$model->removePasswordResetToken ();
			if ($model->save ()) {
				\Yii::$app->session->setFlash ( 'success', 'New password is saved successfully.' );
				return $this->redirect ( [
						'user/login'
				] );
			} else {
				\Yii::$app->session->setFlash ( 'error', 'Error while saving new password.' );
			}
		}
		$this->updateMenuItems ( $model );
		return $this->render ( 'resetpassword', [
				'model' => $newModel
		] );
	}
	
	
	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$this->layout = 'main';
		
		$model = $this->findModel ( $id );
		
		$model->scenario = 'update';
		$old_image = $model->profile_file;
		if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( Yii::$app->request->post () )) {
			$model->profile_file = $old_image;
			$model->saveUploadedFile ( $model, 'profile_file' );
			if ($model->save ()) {
				return $this->redirect ( [ 
						'view',
						'id' => $model->id 
				] );
			}
		}
		$this->updateMenuItems ();
		return $this->render ( 'update', [ 
				'model' => $model 
		] );
	}
	public function actionUpdateApproval($id) {
		$this->layout = 'main';
		
		$model = $this->findModel ( $id );
		$profileModel = UserProfile::find ()->where ( [ 
				'created_by_id' => $id 
		] )->one ();
		$bankModel = Bank::find ()->where ( [ 
				'created_by_id' => $id 
		] )->one ();
		$model->scenario = 'update';
		
		if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( Yii::$app->request->post () )) {
			
			$model->message = $_POST ['User'] ['message'];
			$model->status = $_POST ['User'] ['status'];
			if ($_POST ['User'] ['status'] == User::STATUS_APPROVED) {
				$model->role_id = User::ROLE_WORKER;
				$model->address = $model->approval_address;
			}
			if ($model->save ()) {
				return $this->redirect ( [ 
						'approval-view',
						'id' => $model->id 
				] );
			}
		}
		
		if (! empty ( $profileModel ) && ! empty ( $bankModel )) {
			return $this->render ( 'update-approval', [ 
					'model' => $model,
					'promodel' => $profileModel,
					'bankmodel' => $bankModel 
			] );
		} else {
			return $this->render ( 'update-approval', [ 
					'model' => $model 
			] );
		}
	}
	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {

	    $model = $this->findModel( $id );
	    if ($model->role_id == User::ROLE_ADMIN) {
			
			Yii::$app->getSession ()->setFlash ( 'success', 'You cannot delete Admin' );
			return $this->redirect ( [ 
					'/dashboard/index' 
			] );
		} elseif ($model->role_id == User::ROLE_SUBADMIN) {

            $model->delete ();
            return $this->redirect ( [
                '/user/index'
            ] );
        } elseif ($model->role_id == User::ROLE_CUSTOMER) {
			
			$model->delete ();
			if ($model->status == User::STATUS_DENY) {
				return $this->redirect ( [ 
						'deny-index' 
				] );
				;
			} else {
				return $this->redirect ( [ 
						'customer-index' 
				] );
			}
		} elseif ($model->role_id == User::ROLE_WORKER) {
			$model->delete ();
			if ($model->status == User::STATUS_APPROVED) {
				return $this->redirect ( [ 
						'approval-index' 
				] );
			} else {
				return $this->redirect ( [ 
						'worker-index' 
				] );
			}
		}
	}
	public function actionCustomerDelete($id) {
		$model = $this->findModel ( $id );
		
		$model->Customerdelete ();
		return $this->redirect ( [ 
				'customer-index' 
		] );
	}
	public function actionLogin() {
		$this->layout = "guest-main";
		
		if (! \Yii::$app->user->isGuest) {
			return $this->goHome ();
		}
		
		$model = new LoginForm ();
		if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
			$model->scenario = 'signup';
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate ( $model );
		}
		if ($model->load ( Yii::$app->request->post () ) && $model->validate ()) {
			$user = $model->getUser ();
			if (($user) && ($user->role_id == User::ROLE_ADMIN || $user->role_id == User::ROLE_SUBADMIN) && $model->login ()) {
				return $this->redirect ( [
						'/dashboard/index'
				] );
			}
			$model->addError ( "password", yii::t ( 'app', 'You are not allowed to access this page.' ) );
		}
		return $this->render ( 'login', [ 
				'model' => $model 
		] );
	}
	public function actionDownload($profile_file) {
		$file = UPLOAD_PATH . $profile_file;
		if (file_exists ( $file )) {
			Yii::$app->response->sendFile ( $file );
		}
	}
	public function actionProfileImage() {
		return Yii::$app->user->identity->getProfileImage ();
	}
	public function actionLogout() {
		Yii::$app->user->logout ();
		
		return $this->goHome ();
	}
	public function actionChangepassword($id) {
		$this->layout = 'main';
		$model = $this->findModel ( $id );
		
		$newModel = new User ( [ 
				'scenario' => 'changepassword' 
		] );
		if ($newModel->load ( Yii::$app->request->post () ) && $newModel->validate ()) {
			// if ($model->validatePassword ( $newModel->oldPassword )) {
			$model->setPassword ( $newModel->newPassword );
			if ($model->save ( false )) {
				
				Yii::$app->getSession ()->setFlash ( 'success', 'Password Changed Successfully' );
				return $this->render ( 'changepassword', [ 
						'model' => $newModel 
				] );
			} else {
				return $this->render ( 'changepassword', [ 
						'model' => $newModel 
				] );
			}
		} else {
			
			// }
		}
		return $this->render ( 'changepassword', [ 
				'model' => $newModel 
		] );
	}
	public function actionDashboard() {
		return $this->render ( "/dashboard/index" );
	}
	public function actionImageManager() {
		$response = array ();
		
		// Image types.
		$image_types = array (
				"image/gif",
				"image/jpeg",
				"image/pjpeg",
				"image/jpeg",
				"image/pjpeg",
				"image/png",
				"image/x-png" 
		);
		
		// Filenames in the uploads folder.
		$fnames = scandir ( UPLOAD_PATH );
		
		// Check if folder exists.
		if ($fnames) {
			// Go through all the filenames in the folder.
			foreach ( $fnames as $name ) {
				// Filename must not be a folder.
				if (! is_dir ( $name )) {
					// Check if file is an image.
					if (in_array ( mime_content_type ( UPLOAD_PATH . $name ), $image_types )) {
						// Build the image.
						$img = new \StdClass ();
						$img->url = Yii::$app->urlManager->createAbsoluteUrl ( '/protected/uploads' ) . DIRECTORY_SEPARATOR . $name;
						$img->thumb = Yii::$app->urlManager->createAbsoluteUrl ( '/protected/uploads' ) . DIRECTORY_SEPARATOR . $name;
						$img->name = $name;
						
						// Add to the array of image.
						array_push ( $response, $img );
					}
				}
			}
		} // Folder does not exist, respond with a JSON to throw error.
else {
			$response = new \StdClass ();
			$response->error = "Images folder does not exist!";
		}
		
		$response = json_encode ( $response );
		
		// Send response.
		echo stripslashes ( $response );
	}
	public function actionImageUpload() {
		// Allowed extentions.
		$allowedExts = array (
				"gif",
				"jpeg",
				"jpg",
				"png",
				"blob" 
		);
		// Get filename.
		$temp = explode ( ".", $_FILES ["file"] ["name"] );
		
		// Get extension.
		$extension = end ( $temp );
		
		$finfo = finfo_open ( FILEINFO_MIME_TYPE );
		$mime = finfo_file ( $finfo, $_FILES ["file"] ["tmp_name"] );
		
		if ((($mime == "image/gif") || ($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/x-png") || ($mime == "image/png")) && in_array ( strtolower ( $extension ), $allowedExts )) {
			// Generate new random name.
			$name = sha1 ( microtime () ) . "." . $extension;
			
			// Save file in the uploads folder.
			move_uploaded_file ( $_FILES ["file"] ["tmp_name"], UPLOAD_PATH . $name );
			
			// Generate response.
			$response = new \StdClass ();
			$response->link = Yii::$app->urlManager->createAbsoluteUrl ( '/protected/uploads' ) . DIRECTORY_SEPARATOR . $name;
			echo stripslashes ( json_encode ( $response ) );
		}
	}
	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = User::findOne ( $id )) !== null) {
			
			if (! ($model->isAllowed ()))
				throw new HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
			
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}

	protected function updateMenuItems($model = null) {
		switch (\Yii::$app->controller->action->id) {
			
			case 'add' :
				{
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => Yii::t ( 'app', 'Manage' ),
							'url' => [ 
									'index' 
							],
							'visible' => User::isAdmin ()  || User::isSubAdmin()
					);
				}
				break;
			case 'index' :
//				$this->menu ['add'] = array (
//						'label' => '<span class="glyphicon glyphicon-list"></span>',
//						'title' => Yii::t ( 'app', 'Manage' ),
//						'url' => [
//								'/dashboard/index'
//						],
//						'visible' => User::isAdmin ()  || User::isSubAdmin()
//				);
				break;
			case 'update' :

				break;
			case 'view' :
				{
					
					if ($model != null)
						if ($model->role_id != User::ROLE_ADMIN  && $model->role_id != User::ROLE_CUSTOMER && $model->role_id != User::ROLE_SUBADMIN) {
							$this->menu ['update'] = array (
									'label' => '<span class="glyphicon glyphicon-pencil"></span>',
									'title' => Yii::t ( 'app', 'Update' ),
									'url' => [ 
											'update-approval',
											'id' => $model->id 
									],
									
									'visible' => User::isAdmin () || User::isSubAdmin()
							);
						} else{
							$this->menu ['update'] = array (
									'label' => '<span class="glyphicon glyphicon-pencil"></span>',
									'title' => Yii::t ( 'app', 'Update' ),
									'url' => [ 
											'update',
											'id' => $model->id 
									],
									
									'visible' => User::isAdmin () || User::isSubAdmin()
							);
						}
					if ($model->role_id == User::ROLE_CUSTOMER) {
						$this->menu ['manage'] = array (
								'label' => '<span class="glyphicon glyphicon-list"></span>',
								'title' => Yii::t ( 'app', 'Manage' ),
								'url' => [ 
										'customer-index' 
								],
								'visible' => User::isAdmin () || User::isSubAdmin()
						);
					} elseif ($model->role_id == User::ROLE_WORKER) {
						
						$this->menu ['manage'] = array (
								'label' => '<span class="glyphicon glyphicon-list"></span>',
								'title' => Yii::t ( 'app', 'Manage' ),
								'url' => [ 
										'worker-index' 
								],
								'visible' => User::isAdmin () || User::isSubAdmin()
						);
					} elseif ($model->role_id == User::ROLE_SUBADMIN) {

                        $this->menu ['manage'] = array (
                            'label' => '<span class="glyphicon glyphicon-list"></span>',
                            'title' => Yii::t ( 'app', 'Manage' ),
                            'url' => [
                                'subadmin-index'
                            ],
                            'visible' => User::isAdmin () || User::isSubAdmin()
                        );
                    }
					if ($model->role_id == User::ROLE_CUSTOMER || $model->role_id == User::ROLE_WORKER || $model->role_id == User::ROLE_SUBADMIN) {
						$this->menu ['delete'] = array (
								'label' => '<span class="glyphicon glyphicon-trash"></span>',
								'title' => Yii::t ( 'app', 'Delete' ),
								'url' => [ 
										'delete',
										
										'id' => $model->id 
								],
								'htmlOptions' => [ 
										'data-method' => 'post',
										'data-confirm' => 'Are you sure you want to delete this item?' 
								],
								'visible' => User::isAdmin () 
						);
					}
				}
				break;
		}
	}
}

