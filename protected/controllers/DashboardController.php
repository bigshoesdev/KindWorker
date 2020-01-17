<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\models\Bid;
use app\models\Category;
use app\models\Job;
use app\models\User;
use app\components\TActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;

class DashboardController extends TController {
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
												'add',
										],
										'allow' => true,
										'roles' => [ 
												// '*',
												// '?',
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
                                ],
                                [
                                    'actions' => [
                                        'add'
                                    ],
                                    'allow' => true,
                                    'roles' => [
                                        '?',
                                        '*'
                                    ]
                                ]

                        ]
				] 
		];
	}
	public function actionIndex() {
		$this->updateMenuItems ();

		return $this->render ( 'index' );
	}

    public function actionAdd() {

//        $this->layout = 'main';
//        $model = new User ();
//        $post = \yii::$app->request->post ();
//        $model->role_id = User::ROLE_SUBADMIN;
//        $model->state_id = User::STATE_ACTIVE;
//        //$model->scenario = 'add';
//        if (Yii::$app->request->isAjax && $model->load ($post)) {
//            //$model->scenario = 'add';
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return TActiveForm::validate ( $model );
//        }
//        if ($model->load ($post)) {
//            $model->role_id = User::ROLE_SUBADMIN;
//            $model->state_id = User::STATE_ACTIVE;
//            //$image = UploadedFile::getInstance ( $model, 'profile_file' );
//            $image = $model->saveUploadedFile ( $model, 'profile_file' );
//            if( isset($image['error']) && !empty($image['error']) ) {
//                $model->addError ( "profile_file", \yii::t ( 'app', $image) );
//                return $this->render ( 'add', [
//                    'model' => $model
//                ] );
//            }
//
//            if ($model->validate ()) {
//                $model->generatePasswordResetToken ();
//                $model->setPassword ( $model->password );
//                if ($model->save ()) {
//                    $email = $model->email;
//                    //$view = 'sendPassword';
//                    $sub = "Welcome! You new account is ready" . \Yii::$app->params ['company'];
//
//                    Yii::$app->mailer->compose ( [
//                        'html' => 'sendPassword'
//                    ], [
//                        'user' => $model
//                    ] )->setTo ( $email )->setFrom ( \Yii::$app->params ['adminEmail'] )->setSubject ( $sub )->send ();
//
//                    Yii::$app->getSession ()->setFlash ( 'success', ' SubAdmin Added Successfully.' );
//                    return $this->render ( '/user/view', [
//                        'model' => $model
//                    ] );
//
//                }
//            }
//        }
        return $this->redirect(Url::toRoute(['user/add']));
//        $this->updateMenuItems();
//        return $this->render('add', [
//            'model' => $model
//        ]);
    }

    public static function MonthlySignups() {
	    $date = new \DateTime ();
		$date->modify ( '-12 months' );
		$count = array ();
		for($i = 1; $i <= 12; $i ++) {
			$date->modify ( '+1 months' );
			$month = $date->format ( 'y-m' );
			
			$count [$month] = ( int ) User::find ()->where ( [
					'like',
					'created_on',
					$month 
			] )->count ();
		}
		return $count;
	}

    public static function MonthlyJobSignups() {
        $date = new \DateTime ();
        $date->modify ( '-12 months' );
        $count = array ();
        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 months' );
            $month = $date->format ( 'Y-m' );

            $count [$month] = ( int ) Job::find ()->where ( [
                'like',
                'created_on',
                $month
            ] )->count ();
        }
        return $count;
    }

    public static function MonthlyBidSignups() {
        $date = new \DateTime ();
        $date->modify ( '-12 months' );
        $count = array ();
        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 months' );
            $month = $date->format ( 'y-m' );

            $count [$month] = ( int ) Bid::find ()->where ( [
                'like',
                'created_on',
                $month
            ] )->count ();
        }
        return $count;
    }

    public static function MonthlyCustomerSignups($index) {
        $date = new \DateTime ();
        $date->modify ( '-12 months' );
        $count = array ();

        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 months' );
            $month = $date->format ( 'y-m' );

                $count [$month] = ( int )User::find()->where([
                    'like',
                    'created_on',
                    $month
                ])->andWhere([
                    'role_id' => $index
                ])->count();
        }
        return $count;
    }

    public static function DailySignups() {
        $date = new \DateTime ();
        $date->modify ( '-13 days' );
        $count = array ();
        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 days' );
            $month = $date->format ( 'm-d' );

            $count [$month] = ( int ) User::find ()->where ( [
                'like',
                'created_on',
                $month
            ] )->count ();
        }
        return $count;
    }

    public static function DailyJobSignups() {
        $date = new \DateTime ();
        $date->modify ( '-13 days' );
        $count = array ();
        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 days' );
            $month = $date->format ( 'm-d' );

            $count [$month] = ( int ) Job::find ()->where ( [
                'like',
                'created_on',
                $month
            ] )->count ();
        }
        return $count;
    }

    public static function DailyBidSignups() {
        $date = new \DateTime ();
        $date->modify ( '-13 days' );
        $count = array ();
        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 days' );
            $month = $date->format ( 'm-d' );

            $count [$month] = ( int ) Bid::find ()->where ( [
                'like',
                'created_on',
                $month
            ] )->count ();
        }
        return $count;
    }

    public static function DailyCustomerSignups($index) {
        $date = new \DateTime ();
        $date->modify ( '-13 days' );
        $count = array ();

        for($i = 1; $i <= 12; $i ++) {
            $date->modify ( '+1 days' );
            $month = $date->format ( 'm-d' );

            $count [$month] = ( int )User::find()->where([
                'like',
                'created_on',
                $month
            ])->andWhere([
                'role_id' => $index
            ])->count();
        }
        return $count;
    }

    public static function WeeklySignups() {
	    $date = new \DateTime ();
        $m = strtotime($date->format("y-m-d"));
        $today =   date('l', $m);
        $custom_date = strtotime( date('y-m-d', $m) );
        if ($today == 'Saturday') {
            $week_end = date("y-m-d", $m);
        } else {
            $week_end = date('y-m-d', strtotime('this week next saturday', $custom_date));
        }

        $date = new \DateTime($week_end);
        $date-> modify ( '-83 days' );
        $count = array ();
        $weekly_count = 0;
        $week_count = $date->format("W").'week';
        for($i = 1; $i <= 83; $i ++) {

            $date->modify ( '+1 days' );
            $week = $date->format("W").'week';

            if($week==$week_count){
                $weekly_count = $weekly_count + ( int ) User::find ()->where ( [
                    'like',
                    'created_on',
                    $date->format('y-m-d')
                ] )->count ();

            }else{
                $count[$week]=$weekly_count;
                $weekly_count = 0;
                $week_count=$week;
                $date->modify ( '-1 days' );
            }
        }
        return $count;
    }

    public static function WeeklyJobSignups() {
        $date = new \DateTime ();
        $m = strtotime($date->format("y-m-d"));
        $today =   date('l', $m);
        $custom_date = strtotime( date('y-m-d', $m) );

        if ($today == 'Saturday') {
            $week_end = date("y-m-d", $m);
        } else {
            $week_end = date('y-m-d', strtotime('this week next saturday', $custom_date));
        }

        $date = new \DateTime($week_end);
        $date-> modify ( '-83 days' );
        $count = array ();
        $weekly_count = 0;
        $week_count = 0;
        for($i = 1; $i <= 83; $i ++) {

            $date->modify ( '+1 days' );
            $week = $date->format("W").'week';

            if($week==$week_count){
                $weekly_count = $weekly_count + ( int ) Job::find ()->where ( [
                        'like',
                        'created_on',
                        $date->format('y-m-d')
                    ] )->count ();

            }else{
                $count[$week]=$weekly_count;
                $weekly_count = 0;
                $week_count=$week;
                $date->modify ( '-1 days' );
            }
        }
        return $count;
    }

    public static function WeeklyBidSignups() {
        $date = new \DateTime ();
        $m = strtotime($date->format("y-m-d"));
        $today =   date('l', $m);
        $custom_date = strtotime( date('y-m-d', $m) );

        if ($today == 'Saturday') {
            $week_end = date("y-m-d", $m);
        } else {
            $week_end = date('y-m-d', strtotime('this week next saturday', $custom_date));
        }

        $date = new \DateTime($week_end);
        $date-> modify ( '-83 days' );
        $count = array ();
        $weekly_count = 0;
        $week_count = 0;
        for($i = 1; $i <= 83; $i ++) {

            $date->modify ( '+1 days' );
            $week = $date->format("W").'week';

            if($week==$week_count){
                $weekly_count = $weekly_count + ( int ) Bid::find ()->where ( [
                        'like',
                        'created_on',
                        $date->format('y-m-d')
                    ] )->count ();

            }else{
                $count[$week]=$weekly_count;
                $weekly_count = 0;
                $week_count=$week;
                $date->modify ( '-1 days' );
            }
        }
        return $count;
    }

    public static function WeeklyCustomerSignups($index) {
        $date = new \DateTime ();
        $m = strtotime($date->format("y-m-d"));
        $today =   date('l', $m);
        $custom_date = strtotime( date('y-m-d', $m) );

        if ($today == 'Saturday') {
            $week_end = date("y-m-d", $m);
        } else {
            $week_end = date('y-m-d', strtotime('this week next saturday', $custom_date));
        }

        $date = new \DateTime($week_end);
        $date-> modify ( '-83 days' );
        $count = array ();
        $weekly_count = 0;
        $week_count = 0;
        for($i = 1; $i <= 83; $i ++) {

            $date->modify ( '+1 days' );
            $week = $date->format("W").'week';

            if($week==$week_count){
                $weekly_count = $weekly_count + ( int ) User::find ()->where ( [
                        'like',
                        'created_on',
                        $date->format('y-m-d')
                    ] )->andWhere([
                        'role_id' => $index
                    ])->count ();

            }else{
                $count[$week]=$weekly_count;
                $weekly_count = 0;
                $week_count=$week;
                $date->modify ( '-1 days' );
            }
        }
        return $count;
    }

}
