<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\models\ContactForm;
use app\models\EmailQueue;
use app\models\User;
use Yii;
use yii\web\Response;
use app\components\TActiveForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

class SiteController extends TController {
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
												'contact',
												'about',
												'error' 
										],
										'allow' => true,
										'roles' => [ 
												'*',
												'?',
												'@' 
										] 
								],
								[
										
										'actions' => [
												'index',
												'add',
												'view',
												'update',
												'delete',
												'ajax'
										],
										'allow' => true,
										'matchCallback' => function () {
										return User::isAdmin ();
										}
										] 
						] 
				] 
		];
	}
	public function actions() {
		return [ 
				
				 'error' => [ 
						'class' => 'yii\web\ErrorAction' 
				], 
				
				'captcha' => [ 
						'class' => 'yii\captcha\CaptchaAction',
						'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null 
				] 
		];
	}
	public function actionErrorEmail() {
		$exception = \Yii::$app->errorHandler->exception;
		if ($exception !== null && $exception->status != 404) {
			$que = new EmailQueue ();
			$sub = "There was an Error while visiting this Url: " . \yii::$app->request->url;
			$que->sendNow ( null, $exception->getTraceAsString (), null, $sub );
			return $this->render ( 'error', [ 
					'message' => $exception->getMessage (),
					'name' => 'Error' 
			] );
		}
	}

	
	public function actionIndex() {
		$this->updateMenuItems();
		if (! \Yii::$app->user->isGuest) {
			$this->layout = 'main';
			return $this->redirect('dashboard/index');
		} else {
			$this->layout = 'guest-main';
			return $this->render ( 'index' );
		}
	}
	public function actionContact() {
		$this->layout = 'guest-main';
		$model = new ContactForm ();
		if (Yii::$app->request->isAjax && $model->load ( Yii::$app->request->post () )) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return TActiveForm::validate ( $model );
		}
		if ($model->load ( Yii::$app->request->post () ) && $model->contact ( Yii::$app->params ['adminEmail'] )) {
			Yii::$app->session->setFlash ( 'contactFormSubmitted' );
			return $this->refresh ();
		}
		return $this->render ( 'contact', [ 
				'model' => $model 
		] );
	}
	public function actionAbout() {
		$this->layout = 'guest-main';
		return $this->render ( 'about' );
	}
	
	 protected function updateMenuItems($model = null) {
		// create static model if model is null
		switch ($this->action->id) {
			case 'add' :
				{
					$this->menu [] = array (
							'label' => Yii::t ( 'app', 'Manage' ),
							'url' => array (
									'index' 
							),
							'visible' => User::isAdmin () 
					);
				}
				break;
			default :
			case 'view' :
				{
					$this->menu [] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>Manage',
							'title' => 'Manage',
							'url' => array (
									'index' 
							),
							'visible' => User::isAdmin () 
					);
					
					if ($model != null)
						$this->menu [] = array (
								'label' => Yii::t ( 'app', 'Update' ),
								'url' => array (
										'update',
										'id' => $model->id 
								),
								'visible' => ! User::isAdmin () 
						);
				}
				break;
		}
	} 
	
	
}
