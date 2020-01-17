<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\controllers;

use app\controllers\AvailabilitySlotController;
use app\models\AvailabilitySlot;
use app\models\Rating;
use app\models\search\WorkerSkill;
use app\models\User;
use app\models\Job;
use app\models\Category;
use app\models\Bid;
use app\models\Notification;
use app\models\Transaction;
use app\models\JobImage;
use app\modules\api\controllers\ApiTxController;
use yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * JobController implements the API actions for Job model.
 */
class JobController extends ApiTxController {
	public function behaviors() {
		return ArrayHelper::merge ( parent::behaviors (),[
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
												'get',
												'update',
												'delete',
												'list',
												'custom-job-list',
												'customer-task',
												'worker-review',
												'customer-review',
												'cancel-task',
												'search-job',
												'search-category',
												'add-custom-job',
												'update-custom-job',
                                                'book-worker',
												'release-pay',
												'worker-booking',
												'award-bid',
												'calender-list',
												'job-start',
												'job-complete',
												'job-cancel',
												'check-noty',
												'job-image',
												'market-list',
												'award-cancel-list',
												'job-image-update',
												'worker-cancel',
												'worker-detail',
												're-pause',
												're-post',
												'job-reschedule',
												'update',
                                                'bid-time-check',
                                                'job-remote-complete'
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												'index',
                                                'job-start',
                                                'job-complete',
                                                'update-custom-job',
                                                'get',
												'update',
                                            'custom-job-list',
												'list',
												'check-noty'
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'*' 
										] 
								] 
						] 
				] 
		]);
	}
	/**
	 * awarded and cancel list api for customer side
	 *
	 * @return mixed
	 */
	public function actionAwardCancelList() {
		$data = [ ];
		$user = \yii::$app->user->id;
		$model = Job::find ()->where ( [ 
				'created_by_id' => $user 
		] )->andFilterWhere ( [ 
				'in',
				'state_id',
				[ 
						Job::STATE_AWARDED,
						Job::STATE_CANCEL 
				] 
		] )->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['list'] = $list;
			}
		} else {
			$data ['error'] = 'No Job Found';
		}
		return $this->sendResponse ( $data );
	}

	public function actionCheckNoty($id) {
		$data = [ ];
		$message = 'Testing';
		Notification::newAppNotify ( 1, $id, $message );
		$data ['status'] = self::API_OK;
		$data ['message'] = $message;
		return $this->sendResponse ( $data );
	}
	/**
	 * Add Job Image api for worker side
	 *
	 * @return mixed
	 */
	public function actionJobImage($job_id, $type) {
		$data = [ ];
		$model = new JobImage ();
		$params = Yii::$app->request->post ();
		if ($_FILES) {
			$file1 = UploadedFile::getInstance ( $model, 'image_file' );
			if (! empty ( $file1 )) {
                $filename = 'jobimage_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file1->extension;
			    $file1->saveAs ( UPLOAD_PATH . $filename );
				$model->image_file = $filename;
			}
			$model->type_id = ( int ) $type;
			$model->job_id = $job_id;
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ();
			} else {
				$data ['error'] = $model->getErrorsString ();
			}
		} else {
			$data ['error'] = 'No Data Posted';
		}
		return $this->sendResponse ( $data );
	}
	public function actionJobImageUpdate($id) {
		$data = [ ];
		$model = JobImage::find ()->where ( [ 
				'id' => $id 
		] );
		$params = Yii::$app->request->post ();
		if ($_FILES) {
			$file1 = UploadedFile::getInstance ( $model, 'image_file' );
			$filePath = '';
			if (! empty ( $file1 )) {
                $filename = 'jobimage_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file1->extension;
                $file1->saveAs ( UPLOAD_PATH . $filename );
				$filePath = UPLOAD_PATH . $model->image_file;
                $model->image_file = $filename;
			}
			if ($model->save ()) {
//			    if(file_exists($filePath))
//			        unlink($filePath);
				$data ['status'] = self::API_OK;
				$data ['detail'] = $model->asJson ();
			} else {
				$data ['error'] = $model->getErrorsString ();
			}
		} else {
			$data ['error'] = 'No Data Posted';
		}
		return $this->sendResponse ( $data );
	}
	/**
	 * Job Start api for worker side
	 *
	 * @return mixed
	 */
	public function actionJobStart($job_id) {
		$data = [ ];
        $params = Yii::$app->request->post ();
        $model = Job::find ()->where ( [
				'id' => $job_id 
		] )->one ();
        $jobimage = JobImage::find()->where([
            'job_id' =>$job_id,
            'type_id' => JobImage::TYPE_BEFORE,
            'created_by_id' => \yii::$app->user->id
        ])->one();
        if(empty($jobimage))
        {
            $jobimage = new JobImage();
        }
        if (! empty ( $model )) {
            if(isset($_FILES)) {
                $file1 = UploadedFile::getInstance($jobimage, 'first_file');
                $oldFilePath1='';
                $oldFilePath2='';
                if (!empty ($file1)) {
                    $filename = 'jobimage_first_file' . '-' . time() . '-' . Yii::$app->user->id . '.' . $file1->extension;
                    $file1->saveAs(UPLOAD_PATH . $filename);
                    $oldFilePath1 = UPLOAD_PATH . $jobimage->first_file;
                    $jobimage->first_file = $filename;
                }
                $file2 = UploadedFile::getInstance($jobimage, 'second_file');
                if (!empty ($file2)) {
                    $filename = 'jobimage_second_file' . '-' . time() . '-' . Yii::$app->user->id . '.' . $file2->extension;
                    $file2->saveAs(UPLOAD_PATH . $filename);
                    $oldFilePath2 = UPLOAD_PATH . $jobimage->second_file;
                    $jobimage->second_file = $filename;
                }
                $jobimage->job_id = $job_id;
                $jobimage->type_id = JobImage::TYPE_BEFORE;
                $jobimage->created_by_id = \yii::$app->user->id;
                if($jobimage->save()){
                    if(file_exists($oldFilePath1))
                        unlink($oldFilePath1);
                    if(file_exists($oldFilePath2))
                        unlink($oldFilePath2);
                    $model->state_id = Job::STATE_IN_PROGRESS;
                    if ($model->save()) {
                        $data ['status'] = self::API_OK;
                        $data ['detail'] = $model->asCustomerJson();
                        $data ['message'] = 'Job has been started by ' . \yii::$app->user->identity->email;
                       // Notification::notification(new Job (), $data ['message'], $model->id, $model->created_by_id);
                    } else {
                        $data ['error'] = $model->getErrors();
                    }
                } else{
                    $data ['error'] = $jobimage->getErrors();
                }

            } else{
                $data ['error'] = 'No data post';
            }
		} else {
			$data ['error'] = 'No job found';
		}
		return $this->sendResponse ( $data );
	}
	/**
	 * Job cancel api for customer side
	 *
	 * @return mixed
	 */
	public function actionJobCancel($job_id) {
		$data = [ ];
		$model = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
		if (! empty ( $model )) {
			$model->state_id = Job::STATE_CANCEL;
			if (\yii::$app->user->identity->id == $model->worker_id) {
				$model->cancel_by = Job::CANCEL_BY_WORKER;
			} else {
				$model->cancel_by = Job::CANCEL_BY_CUSTOMER;
            }

            if ($model->save ()) {
				$data ['status'] = self::API_OK;
				//$data ['details'] = $model->asJson ();
				$data ['message'] = 'Your Job has been Canceled by ' . \yii::$app->user->identity->email;
//                if (\yii::$app->user->identity->role_id == User::ROLE_WORKER) {
//                    Notification::notification ( new Job (), $data ['message'], $model->id, $model->created_by_id );
//                } else {
//                    Notification::notification ( new Job (), $data ['message'], $model->id, $model->worker_id );
//                }
			} else {
				$data ['error'] = $model->getErrors ();
			}
		} else {
			$data ['error'] = 'No job found';
		}
		return $this->sendResponse ( $data );
	}

    /**
     * Job cancel api for customer side
     *
     * @return mixed
     */
    public function actionJobReschedule() {
        $data = [ ];
        $params = Yii::$app->request->post ();
        $job_id = $params['job_id'];
        $date = $params['date'];
        $model = Job::find ()->where ( [
            'id' => $job_id
        ] )->one ();
        if (! empty ( $model )) {
            $model->date = $date;
            if ($model->save ()) {
                $data ['status'] = self::API_OK;
                $data ['message'] = 'Your Job has been Schduled by ' . \yii::$app->user->identity->email;
//                if (\yii::$app->user->identity->role_id == User::ROLE_WORKER) {
//                    Notification::notification ( new Job (), $data ['message'], $model->id, $model->created_by_id );
//                } else {
//                    Notification::notification ( new Job (), $data ['message'], $model->id, $model->worker_id );
//                }
            } else {
                $data ['error'] = $model->getErrors ();
            }
        } else {
            $data ['error'] = 'No job found';
        }
        return $this->sendResponse ( $data );
    }
	/**
	 * Job cancel api for worker side
	 *
	 * @return mixed
	 */
	public function actionWorkerCancel($job_id) {
		$data = [ ];
		$model = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
		if (! empty ( $model )) {
			$model->state_id = Job::STATE_CANCEL;
			if (\yii::$app->user->identity->role_id == User::ROLE_WORKER) {
				$model->cancel_by = 'Worker Canceled this job';
			} else {
				$model->cancel_by = 'Customer Canceled this job';
			}
			if ($model->save ()) {
				$data ['status'] = self::API_OK;
				$data ['details'] = $model->asJson ();
				$data ['message'] = 'Your Job has been Canceled by ' . \yii::$app->user->identity->email;
				Notification::notification ( new Job (), $data ['message'], $model->id, $model->created_by_id );
			} else {
				$data ['error'] = $model->getErrors ();
			}
		} else {
			$data ['error'] = 'No job found';
		}
		return $this->sendResponse ( $data );
	}

    public function actionWorkerReview($job_id) {
        $data = [ ];
        $model = Job::find ()->where ( [
            'id' => $job_id
        ] )->one ();
        $params = Yii::$app->request->post ();
        if (! empty ( $model )) {
            $rating = Rating::find ()->where ( [
                'model_id' => $job_id,
                'created_by_id' => \yii::$app->user->id,
                'type_id'=>Rating::TYPE_CUSTOMER
            ] )->one ();
            if(empty($rating))
            {
                $rating = new Rating();
                $rating->created_by_id = \yii::$app->user->id;
                $rating->model_id = $job_id;
                $rating->type_id = Rating::TYPE_CUSTOMER;
                $rating->model_type = get_class($model);
            }
            if($rating->load($params))
            {
                if ($rating->save ()) {
                    $data ['status'] = self::API_OK;
                    $data ['detail'] = $rating->asJson ();
                    $data ['message'] = 'Your Job has been rated by ' . \yii::$app->user->identity->email;
                    //Notification::notification ( new Rating(), $data ['message'], $rating->id, $rating->user_id );
                } else {
                    $data ['error'] = $rating->getErrors ();
                }
            } else{
                $data ['error'] = 'No data post';
            }
        } else {
            $data ['error'] = 'No job found';
        }
        return $this->sendResponse ( $data );
    }

    public function actionCustomerReview($job_id) {
        $data = [ ];
        $model = Job::find ()->where ( [
            'id' => $job_id
        ] )->one ();
        $params = Yii::$app->request->post ();
        if (! empty ( $model )) {
            $rating = Rating::find ()->where ( [
                'model_id' => $job_id,
                'created_by_id' => \yii::$app->user->id,
                'type_id'=>Rating::TYPE_CUSTOMER
            ] )->one ();
            if(empty($rating))
            {
                $rating = new Rating();
                $rating->created_by_id = \yii::$app->user->id;
                $rating->model_id = $job_id;
                $rating->type_id = Rating::TYPE_CUSTOMER;
            }
            if($rating->load($params))
            {
                if ($rating->save ()) {
                    $data ['status'] = self::API_OK;
                    $data ['detail'] = $rating->asJson ();
                    $data ['message'] = 'Your Job has been rated by ' . \yii::$app->user->identity->email;
                    Notification::notification ( new Rating(), $data ['message'], $rating->id, $rating->user_id );
                } else {
                    $data ['error'] = $rating->getErrors ();
                }
            } else{
                $data ['error'] = 'No data post';
            }
        } else {
            $data ['error'] = 'No job found';
        }
        return $this->sendResponse ( $data );
    }

    /**
     * Worker Details api for worker side
     *
     * @return mixed
     */
    public function actionWorkerDetail($id) {
        $data = [ ];
        $model = User::find ()->where ( [
            'id' => $id
        ] )->one ();
        if (! empty ( $model )) {
            $data ['status'] = self::API_OK;
            $data ['details'] = $model->asWorkerJson ();
        } else {
            $data ['error'] = 'No worker Found';
        }
        return $this->sendResponse ( $data );
    }

	/**
	 * Job Completed api worker side
	 *
	 * @return mixed
	 */
	public function actionJobComplete($job_id) {
		$data = [ ];
		$model = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
        $jobimage = JobImage::find()->where([
            'job_id' =>$job_id,
            'type_id' => JobImage::TYPE_AFTER,
            'created_by_id' => \yii::$app->user->id
        ])->one();
        if(empty($jobimage))
        {
            $jobimage = new JobImage();
        }
        $params = Yii::$app->request->post ();
        if (! empty ( $model )) {
            if(isset($_FILES)) {
                $file1 = UploadedFile::getInstance($jobimage, 'first_file');
                $oldFilePath1='';
                $oldFilePath2='';
                if (!empty ($file1)) {
                    $filename = 'jobimage_complete_first_file' . '-' . time() . '-' . Yii::$app->user->id . '.' . $file1->extension;
                    $file1->saveAs(UPLOAD_PATH . $filename);
                    $oldFilePath1 = UPLOAD_PATH . $jobimage->first_file;
                    $jobimage->first_file = $filename;
                }
                $file2 = UploadedFile::getInstance($jobimage, 'second_file');
                if (!empty ($file2)) {
                    $filename = 'jobimage_complete_second_file' . '-' . time() . '-' . Yii::$app->user->id . '.' . $file2->extension;
                    $file2->saveAs(UPLOAD_PATH . $filename);
                    $oldFilePath2 = UPLOAD_PATH . $jobimage->second_file;
                    $jobimage->second_file = $filename;
                }
                $jobimage->job_id = $job_id;
                $jobimage->type_id = JobImage::TYPE_AFTER;
                $jobimage->created_by_id = \yii::$app->user->id;
                if($jobimage->save()){
                    if(file_exists($oldFilePath1))
                        unlink($oldFilePath1);
                    if(file_exists($oldFilePath2))
                        unlink($oldFilePath2);
                    $model->state_id = Job::STATE_COMPLETE;
                    if ($model->save ()) {
                        $data ['status'] = self::API_OK;
                        $data ['detail'] = $model->asCustomerJson ();

                        $data ['message'] = 'Job has been Completed by ' . \yii::$app->user->identity->email;
                       // Notification::notification ( new Job (), $data ['message'], $model->id, $model->created_by_id );
                    } else {
                        $data ['error'] = $model->getErrors ();
                    }
                } else{
                    $data ['error'] = $jobimage->getErrors ();
                }
            } else{
                $data ['error'] = 'No data post';
            }
        } else {
			$data ['error'] = 'No job found';
		}
		return $this->sendResponse ( $data );
	}
	/**
	 * Calender list worker side
	 *
	 * @return mixed
	 */
	public function actionCalenderList($date) {
		$data = [ ];
		$model = Job::find ()->where ( [ 
				'date' => $date,
				'state_id' => Job::STATE_AWARDED,
				'worker_id' => \yii::$app->user->id 
		] )->all ();
		if (! empty ( $model )) {
			
			$list = [ ];
			foreach ( $model as $mod ) {
				
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['list'] = $list;
			}
		} else {
			$data ['error'] = 'No Task for the day';
		}
		return $this->sendResponse ( $data );
	}
	/**
	 * Job Completed api customer side
	 *
	 * @return mixed
	 */
	public function actionAwardBid($job_id, $bid_id) {
		$data = [ ];
		$job = Job::find ()->where ( [ 
				'id' => $job_id,
				'job_type' => Job::JOB_TYPE_CUSTOM 
		] )->one ();
		$Bid = Bid::find ()->where ( [ 
				'job_id' => $job_id,
				'id' => $bid_id 
		] )->one ();
		
		if (! empty ( $Bid ) && ! empty ( $job )) {
			$Bid->type_id = Bid::TYPE_AWARD;
			if ($Bid->save ()) {
				$job->worker_id = $Bid->created_by_id;
				$job->date = $Bid->date;
				$job->slot_id = $Bid->slot_id;
				$job->state_id = Job::STATE_AWARDED;
				if ($job->save ()) {
                    $slot = AvailabilitySlot::find()->where([
                        'job_id' => $job_id
                    ])->one();
                    $slot->total_hour = $Bid->deliver_in != 0 ? $Bid->deliver_in * 24 : 1 ;
                    $slot->save();
					$data ['status'] = self::API_OK;
					$data ['message'] = 'You Are Awarded For New Job ' . \yii::$app->user->identity->email;
					//Notification::notification ( new Job (), $data ['message'], $job->id, $job->worker_id );
					$data ['detail'] = $job;
				} else {
					$data ['error'] = $job->getErrors ();
				}
			} else {
				$data ['error'] = $Bid->getErrors ();
			}
		}
        else {
            $data ['error'] = 'This Job is not existed';
        }
		return $this->sendResponse ( $data );
	}

	/**
	 * Job Completed api worker side
	 *
	 * @return mixed
	 */
	public function actionWorkerBooking($state_id) {
		$data = [ ];
		$params = \yii::$app->request->bodyParams;
		
		if ($state_id == Job::STATE_AWARDED) {
			$model = Job::find ()->where ( [
					'worker_id' => \yii::$app->user->id 
			] )->andFilterWhere ( [ 
					'in',
					'state_id',
					[ 
                        Job::STATE_AWARDED,
                        Job::STATE_IN_PROGRESS
					] 
			] )->all ();
		} else {
			$model = Job::find ()->where ( [ 
					'worker_id' => \yii::$app->user->id,
					'state_id' => $state_id 
			] )->all ();
		}
		
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['status'] = self::API_OK;
				$data ['details'] = $list;
			}
		} else {
			$data ['error'] = 'No Booking Available';
		}
		
		return $this->sendResponse ( $data );
	}

	/**
	 * Job Completed api customer side
	 *
	 * @return mixed
	 */
	public function actionAddCustomJob() {
		$data = [ ];
		$model = new Job ();
		
        $params = Yii::$app->request->post ();
        $model->scenario = "add-custom-job";
        if(isset($_FILES) && $model->load($params)){
            $date = date_create($params['Job']['strDate']);
            $model->date = date_format($date, 'Y-m-d');
            $model->created_on = date_format($date, 'Y-m-d H:i:s');
            $model->updated_on = date_format($date, 'Y-m-d H:i:s');
            $file1 = UploadedFile::getInstance($model, 'first_file');
            if (! empty ( $file1 )) {
                $filename = 'customjobimage_first_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file1->extension;
                $file1->saveAs ( UPLOAD_PATH . $filename);
                $model->first_file = $filename;
            }
            $file2 = UploadedFile::getInstance ( $model, 'second_file' );
            if (! empty ( $file2 )) {
                $filename = 'customjobimage_second_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file2->extension;
                $file2->saveAs ( UPLOAD_PATH . $filename );
                $model->second_file = $filename;
            }
            $model->job_type = Job::JOB_TYPE_CUSTOM;
            $model->state_id = Job::STATE_IN_BID_PROGRESS;
            if ($model->save ()) {
                $slot = new AvailabilitySlot();
                $slot->job_id = $model->id;
                $slot->start_hour = $params['Availability']['hour'];
                $slot->total_hour = 0;//$params['Job']['total_hour'];
                $slot->state_id = AvailabilitySlot::STATE_ACTIVE;
                $slot->type_id = $params['Job']['type_id'];
                $slot->day = $params['Availability']['day_id'];
                $slot->created_by_id = \yii::$app->user->identity->getId();

                $slot->save();

                $data ['status'] = self::API_OK;
                $data ['detail'] = $model->asJson ();
            } else {
                $data ['error'] = $model->getErrorsString ();
            }
        } else {
            $data ['error'] = 'No Data Posted';
        }
		return $this->sendResponse ( $data );
	}

    public function actionUpdateCustomJob($job_id) {
        $data = [ ];
        $model = Job::find ()->where([
            'id' => $job_id,
            'job_type' => Job::JOB_TYPE_CUSTOM,
            'state_id' => Job::STATE_IN_BID_PROGRESS
        ])->one();
        if(empty($model))
        {
            $data ['error'] = 'Job is not find';
            return $this->sendResponse ( $data );
        }
        $params = Yii::$app->request->post ();
        $model->scenario = "add-custom-job";
        if(isset($_FILES) && $model->load($params)){
            $file1 = UploadedFile::getInstance($model, 'first_file');
            $oldFilePath1 = '';
            $oldFilePath2 = '';
            if (! empty ( $file1 )) {
                $filename = 'customjobimage_first_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file1->extension;
                $file1->saveAs ( UPLOAD_PATH . $filename );
                $oldFilePath1 = UPLOAD_PATH . $model->first_file;
                $model->first_file = $filename;
            }
            $file2 = UploadedFile::getInstance ( $model, 'second_file' );
            if (! empty ( $file2 )) {
                $filename = 'customjobimage_second_file' . '-' . time () . '-' . Yii::$app->user->id . '.' . $file2->extension;
                $file2->saveAs ( UPLOAD_PATH . $filename );
                $oldFilePath2 = UPLOAD_PATH . $model->second_file;
                $model->second_file = $filename;
            }
            //$model->job_type = Job::JOB_TYPE_CUSTOM;
            //$model->state_id = Job::STATE_IN_BID_PROGRESS;
            if ($model->save ()) {
                if(file_exists($oldFilePath1))
                    unlink($oldFilePath1);
                if(file_exists($oldFilePath2))
                    unlink($oldFilePath2);

                $data ['status'] = self::API_OK;
                $data ['detail'] = $model->asJson ();
            } else {
                $data ['error'] = $model->getErrorsString ();
            }
        } else {
            $data ['error'] = 'No Data Posted';
        }
        return $this->sendResponse ( $data );
    }

    public function actionUpdate($job_id) {
		$data = [ ];
		$user = Yii::$app->user->id;
		$params = \Yii::$app->request->bodyParams;
		
		$bidModel = Bid::find ()->where ( [ 
				'job_id' => $job_id 
		] )->one ();
		if (! empty ( $bidModel )) {
			$data ['error'] = 'Bid already has been submitted you cannot update';
		} else {
			$job = Job::find ()->where ( [ 
					'id' => $job_id 
			] )->one ();
			
			if (! empty ( $job )) {
				
				if ($job->load ( $params )) {
					
					if ($job->save ()) {
						$data ['status'] = self::API_OK;
						$data ['detail'] = $job->asJson ();
					} else {
						$data ['error'] = Yii::t ( 'app', 'Unable To Save Data' );
					}
				} else {
					$data ['error'] = Yii::t ( 'app', 'No Data Posted' );
				}
			} else {
				$data ['error'] = Yii::t ( 'app', 'Job Not Found' );
			}
		}
		
		return $this->sendResponse ( $data );
	}

	public function actionBookWorker() {
		$data = [ ];
		$model = new Job ();
		$flag = false;
        $model->scenario = "book-worker";
		$params = Yii::$app->request->post ();
        $user = \yii::$app->user->identity;

        if ($model->load($params )) {
			if ($model->worker_id == $user->id) {
				$data ['error'] = 'You cannot book yourself for the Job';
				return $this->sendResponse ( $data );
			} else {
				$count = Job::find ()->where ( [ 
						'worker_id' => $model->worker_id,
						'date' => $model->date
				] )->count ();
				if (! empty ( $extModel )) {
					if ($count < 4) {
						$data ['error'] = 'Worker is busy with another Task';
						return $this->sendResponse ( $data );
					} else {
						$flag = true;
					}
				} else {
					$flag = true;
				}
			}
		} else {
			$data ['error'] = 'No Data Posted';
			return $this->sendResponse ( $data );
		}
		if ($flag == true) {
			$model->job_type = Job::JOB_TYPE_BOOOKED;
			$model->state_id = Job::STATE_AWARDED;
			$model->estimated_price = strval($params['Job']['total_price'] * (100 - \Yii::$app->params['application_fee_pro']));
			$model->created_by_id = $user->id;
			if ($model->save ()) {
                //\Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
                $slot = new AvailabilitySlot();
                $slot->job_id = $model->id;
                $slot->start_hour = $params['Availability']['hour'];
                $slot->total_hour = $params['Job']['total_hour'];
                $slot->state_id = AvailabilitySlot::STATE_ACTIVE;
                $slot->type_id = $params['Job']['type_id'];
                $slot->day = $params['Availability']['day_id'];
                $slot->created_by_id = \yii::$app->user->identity->getId();
                if(!$slot->save())
                {
                    $model->delete();
                    $data ['error'] = $slot->getErrorsString();
                    return $this->sendResponse ( $data );
                }else{
                    $model->slot_id = $slot->id;
                    $model->save();

                    $transaction = new Transaction();
                    try {
//                    $stripe_charge = \Stripe\Charge::create ( array (
//                        "amount" => $model->total_price * 100,
//                        "description" => "Payment Charge",
//                        "source" => $params ['Pay'] ['token'],
//                        "currency" => "USD"
//                    ) );

                        if (true) {//$stripe_charge->status == "succeeded") {
                            $transaction->reciever_id = $model->worker_id;
                            $transaction->charge_id = "1111111";//$stripe_charge->id;
                            $transaction->transaction_num = "2222222";///$stripe_charge->balance_transaction;
                            $transaction->model_type = get_class($model);
                            $transaction->model_id = $model->id;
                            $transaction->amount = $model->total_price;
                            $transaction->currency = 'USD';
                            $transaction->state_id = Transaction::STATE_CHARGE;
                            $transaction->created_by_id = $user->id;
                            if ($transaction->save()) {
                                $data ['status'] = self::API_OK;
                                $data ['detail'] = $model->asJson ();
                                $message = 'You have been Booked for the New Job from ' . $user->email;
//                            Notification::notification ( new Job (), $message, $model->id, $model->worker_id );
                            } else {

                                $model->delete();
                                $data ['error'] = $transaction->getErrorsString();
                            }
                        } else {
                            $model->delete();
                            $data ['error'] = 'Pay Charge is failed';
                        }
                    } catch ( \Exception $e ) {
                        $model->delete();
                        $data ['error'] = $e->getMessage ();
                    }
                }
            } else {
				$data ['error'] = $model->getErrorsString ();
			}
		}
		
		return $this->sendResponse ( $data );
	}

	public function actionReleasePay()
    {
        $data = [ ];
//        \Stripe\Stripe::setApiKey ( \Yii::$app->params ['stripe_test_key'] );
        $user = \yii::$app->user->identity;
        $requestData = Yii::$app->request->post ();
        $rating = new Rating();
        if($rating->load($requestData)){
            $transaction = Transaction::find()->where([
                'model_id' => $requestData['Pay']['model_id'],
                'reciever_id' => $requestData['Pay']['receiver_id'],
                'state_id'=>Transaction::STATE_CHARGE,
                'created_by_id' => $user->id
            ])->one();
            if (!empty($transaction)) {
                $job_model = Job::find()->where([
                    'id'=>$requestData['Pay']['model_id']
                ])->one();
                if(!empty($job_model)){
                    try {
//                        \Stripe\Transfer::create ( array (
//                            "amount" => $transaction->amount * (100 - \Yii::$app->params['application_fee_pro']) ,
//                            "currency" => "USD",
//                            "description" => "Payment Released",
//                            "source_transaction" => $transaction->charge_id,
//                            "destination" => $transaction->reciever->stripe_customer_id
//                        ) );
                        if(intval($requestData['Pay']['tip'])>0){
//                            \Stripe\Charge::create(array(
//                                "amount" => $requestData['Pay']['tip']*100,
//                                "currency" => "usd",
//                                "source" => $requestData['Pay']['token'],
//                                "destination" => array(
//                                    "amount" => $requestData['Pay']['tip'] * (100 - \Yii::$app->params['application_fee_pro']),
//                                    "account" => $transaction->reciever->stripe_customer_id
//                                ),
//                            ));
                        }
                        $rating->user_id = $requestData['Pay']['receiver_id'];
                        $rating->model_type = get_class($job_model);
                        $rating->model_id = $requestData['Pay']['model_id'];
                        $rating->type_id = Rating::TYPE_WORKER;
                        $rating->created_by_id = $user->id;
                        $rating->save();
                        $oldjob_state = $job_model->state_id;
                        $job_model->state_id = Job::STATE_COMPLETE;
                        if($job_model->save()){
                            $transaction->state_id = Transaction::STATE_SUCCESS;
                            if ($transaction->save()) {
                                $data ['status'] = self::API_OK;
                                $data ['detail'] = $job_model->asJson ();
                                $message = 'You have been Released for ' . $job_model->title . ' Job from ' . $user->email;
//                                Notification::notification ( new Job (), $message, $job_model->id, $job_model->worker_id );
                            } else {
                                $rating->delete();
                                $job_model->state_id = $oldjob_state;
                                $job_model->save();
                                $data ['error'] = $transaction->getErrorsString();
                            }
                        } else{
                            $rating->delete();
                            $data ['error'] = $job_model->getErrorsString ();
                        }
                    } catch ( \Exception $e ) {
                        $data ['error'] = $e->getMessage ();
                    }
                } else{
                    $data ['error'] = 'Job is not found.';
                }
            } else {
                $data ['error'] = 'Transaction is not found.';
            }
        } else{
            $data ['error'] = 'No data posted';
        }

        return $this->sendResponse ( $data );
    }

    public function getPaymentMethodToken($paymentnonce)
    {
        $user = User::find()->where([
            'id' => \yii::$app->user->id
        ])->one();
        if(empty($user)){
            return null;
        }
        if(!empty($paymentnonce)) {
            $braintree = Yii::$app->braintree;
            $result = $braintree->call('PaymentMethod', 'create', [
                'customerId' => $user->braintree_id,
                'paymentMethodNonce' => $paymentnonce,
                'options' => [
                    'failOnDuplicatePaymentMethod' => true
                ]
            ]);
            $paymentmethod = Payment::find()->where([
                'paymentmethod_token' => $result->token,
                'customer_id' => $user->braintree_id
            ])->one();
            if (empty($paymentmethod)) {
                $paymentmethod = new Payment();
                $paymentmethod->customer_id = $user->braintree_id;
                $paymentmethod->paymentmethod_token = $paymentnonce;
                $paymentmethod->created_by_id = $user->id;
                if($paymentmethod->save()){
                    return $paymentmethod;
                }else{
                    $paymentmethod->delete();
                }
                return null;
            }
            return $paymentmethod;
        }
        return null;
    }

    public function getTransaction($paymentmethod, $amount)
    {
        if(!empty($paymentmethod) && !empty($amount)) {

            $braintree = Yii::$app->braintree;
            $result = $braintree->call('Transaction', 'sale', [
                'amount' => $amount,
                'paymentMethodToken' => $paymentmethod->paymentmethod_token,
                'options' => [
                    'storeInVault' => true
                ]
            ]);
            if ($result->success) {
                return true;
            }
        }
        return false;
    }

    public function actionSearchJob() {
		$data = [ ];
		$searchModel = new \app\models\search\Job ();
		
		// $job_title='';
		// $type = '';
		// $params = \Yii::$app->request->bodyParams;
		
		// if(isset($params['Job']['job_title']) && ! empty ( $params ['Job'] ['job_title'] ))
		// {
		// $job_title=$params['Job']['job_title'];
		// }
		// if(isset($params['Job']['type_id']) && ! empty ( $params ['Job'] ['type_id'] ))
		// {
		// $type=$params['Job']['type_id'];
		// }
		
		$dataProvider = $searchModel->searchJob ( \yii::$app->request->post () );
		
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asJson ();
				$data ['list'] = $list;
			}
			
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = "Not Found";
		}
		
		return $this->sendResponse ( $data );
	}

	public function actionMarketList($page=null)
    {
        $data = [ ];
        $user_id = Yii::$app->user->id;

        $skills = WorkerSkill::find()->where(['created_by_id' => $user_id])->all();
        $skilllist = [];
        foreach ($skills as $skill)
        {
            $skilllist[] = $skill->sub_category_id;
        }

        if (!empty($skilllist)) {
            $skillstr = implode(',', $skilllist);
            $searchModel = new \app\models\search\Job ();
            $dataProvider = $searchModel->searchCustomJobList ( $skillstr, $page);
        }

        if (!empty($dataProvider)) {
            $data ['pageSize'] = $dataProvider->pagination->pageSize;
            $data ['pageCount'] = $dataProvider->pagination->pageCount;

            foreach ( $dataProvider->models as $model ) {
                $list [] = $model->asCustomerJobJson ();
                $data ['detail'] = $list;
            }

            $data ['status'] = self::API_OK;
        } else {
            $data ['error'] = "Not Found";
        }

        return $this->sendResponse ( $data );
    }

    /**
	 * Lists all Job models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$data = [ ];
		$model = Job::find ()->all ();
		if (! empty ( $model )) {
			$list = [ ];
			foreach ( $model as $mod ) {
				$list [] = $mod->asJson ();
			}
			if (! empty ( $list )) {
				$data ['data'] = $list;
			}
		} else {
			$data ['status'] = self::API_NOK;
			$data ['error'] = 'No Job Found';
		}
		
		return $this->sendResponse ( $data );
	}
	/**
	 * Displays a single app\models\Job model.
	 *
	 * @return mixed
	 */
	public function actionGet($id) {
		$data = [ ];
		$model = Job::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		if (! empty ( $model )) {
			$data ['status'] = self::API_OK;
			$data ['detail'] = $model->asJson ();
		} else {
			$data ['status'] = self::API_NOK;
			$data ['error'] = 'No Job Found';
		}
		return $this->sendResponse ( $data );
	}
	
	/**
	 * Updates an existing Job model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	/*
	 * public function actionUpdate($id) {
	 * $data = [ ];
	 * $model = $this->findModel ( $id );
	 * if ($model->load ( Yii::$app->request->post () )) {
	 *
	 * if ($model->save ()) {
	 *
	 * $data ['status'] = self::API_OK;
	 *
	 * $data ['detail'] = $model;
	 * } else {
	 * $data ['error'] = $model->getErrors ();
	 * }
	 * } else {
	 * $data ['error_post'] = 'No Data Posted';
	 * }
	 *
	 * return $this->sendResponse ( $data );
	 * }
	 */
	
	/**
	 * Deletes an existing Job model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionRePost($job_id) {
		$data = [ ];
		$user = Yii::$app->user->id;
		$jobModel = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
		$bidModel = Bid::find ()->where ( [ 
				'job_id' => $job_id 
		] )->all ();
		if (! empty ( $jobModel )) {
			
			$jobModel->state_id = Job::STATE_IN_BID_PROGRESS;
			
			if ($jobModel->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $jobModel->asJson ();
				if (! empty ( $bidModel )) {
					foreach ( $bidModel as $bid )
						$bid->delete ();
				}
			} else {
				$data ['error'] = $bidModel->getErrors ();
			}
		}
		return $this->sendResponse ( $data );
	}
	public function actionDelete($job_id) {
		$data = [ ];
		$user = Yii::$app->user->id;
		$jobModel = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
		if (! empty ( $jobModel )) {
			
			$jobModel->state_id = Job::STATE_DELETE;
			$oldFilePath1 = UPLOAD_PATH . $jobModel->first_file;
			$oldFilePath2 = UPLOAD_PATH . $jobModel->second_file;
			if ($jobModel->save ()) {
//			    if(file_exists($oldFilePath1))
//			        unlink($oldFilePath1);
//              if(file_exists($oldFilePath2))
//                  unlink($oldFilePath2);
			    $data ['status'] = self::API_OK;
				$data ['message'] = 'You have successfuly delete this job';
			} else {
				$data ['error'] = $jobModel->getErrors ();
			}
		}
		return $this->sendResponse ( $data );
	}
	public function actionRePause($job_id) {
		$data = [ ];
		$user = Yii::$app->user->id;
		$params = \Yii::$app->request->bodyParams;
		
		$jobModel = Job::find ()->where ( [ 
				'id' => $job_id 
		] )->one ();
		
		if (! empty ( $jobModel )) {
			$jobModel->state_id = Job::STATE_PAUSE;
			if ($jobModel->save ()) {
				$data ['status'] = self::API_OK;
				$data ['detail'] = $jobModel->asJson ();
			} else {
				$data ['error'] = $jobModel->getErrors ();
			}
		}
		return $this->sendResponse ( $data );
	}

	public function actionList($state, $page = null) {
		$data = [ ];
		$list = [ ];
		//$params = \Yii::$app->request->bodyParams;
        $user = \yii::$app->user->id;
        if($state==1){
            $query = Job::find ()->where ( [
                'worker_id' => $user,
            ])->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_COMPLETE
                ]
            ] );
        }
        elseif ($state==2){
            $query = Job::find ()->where ( [
                'worker_id' => $user,
            ] )->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_CANCEL,
                    Job::STATE_DELETE,
                    Job::STATE_DISPUTE,
                    Job::STATE_PAUSE
                ]
            ] );
        }
        else{
            $query = Job::find ()->where ( [
                'worker_id' => $user,
            ] )->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_IN_BID_PROGRESS,
                    Job::STATE_IN_PROGRESS,
                    Job::STATE_AWARDED
                ]
            ] );
        }
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '10',
						'page' => $page 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'date' => SORT_DESC
						] 
				] 
		] );
		if (count ( $dataProvider->models ) > 0) {
            $list = [];
            $list1 = [];
            $mine = [];
            $date = '';

            foreach ( $dataProvider->models as $model ) {
                $tmp  = $model->asCustomerJson ();
                if(!empty($date) && $date!=$tmp['date'])
                {
                    $list["date"] = $date;
                    $list["value"] = $list1;
                    $mine[] = $list;
                    $list1 = [];
                }
                $list1[]=$tmp;
                $date = $tmp['date'];
            }
            $list["date"] = $date;
            $list["value"] = $list1;
            $mine[] = $list;
            $data ['status'] = self::API_OK;
            $data ['detail'] = $mine;
			$data ['pageCount'] = isset ( $page ) ? $page : '0';
			$data ['totalPage'] = isset ( $dataProvider->pagination->pageCount ) ? $dataProvider->pagination->pageCount : '0';
		} else {
			$data ['error'] = \yii::t ( 'app', 'Not Found' );
		}

		return $this->sendResponse ( $data );
	}

	public function actionCustomJobList($page = null) {
		$data = [ ];
		$list = [ ];
		$user = \yii::$app->user->id;
		$query = Job::find ()->where ( [ 
				'created_by_id' => $user,
				'job_type' => Job::JOB_TYPE_CUSTOM 
		] )->andFilterWhere ( [ 
				'in',
				'state_id',
				[ 
						Job::STATE_IN_BID_PROGRESS 
				] 
		] );
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '10',
						'page' => $page 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				
				$data ['list'] [] = $model->asJson ();
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = 'No Custom Job Found';
		}
		return $this->sendResponse ( $data );
	}
	public function actionCustomerTask($state, $page = null) {
	    // $state = 0: Open 1: Complete 2: Unfulfill
		$data = [ ];
		$list = [ ];
		$user = \yii::$app->user->id;
        if($state==1){
            $query = Job::find ()->where ( [
                'created_by_id' => $user,
            ])->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_COMPLETE
                ]
            ] );
        }
        elseif ($state==2){
            $query = Job::find ()->where ( [
                'created_by_id' => $user,
            ] )->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_CANCEL,
                    Job::STATE_DELETE,
                    Job::STATE_DISPUTE,
                    Job::STATE_PAUSE
                ]
            ] );
        }
        else{
            $query = Job::find ()->where ( [
                'created_by_id' => $user,
            ] )->andFilterWhere ( [
                'in',
                'state_id',
                [
                    Job::STATE_IN_BID_PROGRESS,
                    Job::STATE_IN_PROGRESS,
                    Job::STATE_AWARDED
                ]
            ] );
        }
		$dataProvider = new \yii\data\ActiveDataProvider ( [
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '10',
						'page' => $page 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'date' => SORT_DESC
						] 
				] 
		] );
		if (count ( $dataProvider->models ) > 0) {
            $list = [];
            $list1 = [];
            $mine = [];
            $date = '';

            foreach ( $dataProvider->models as $model ) {
                $tmp  = $model->asJson ();
                if(!empty($date) && $date!=$tmp['date'])
                {
                    $list["date"] = $date;
                    $list["value"] = $list1;
                    $mine[] = $list;
                    $list1 = [];
                }
                $list1[]=$tmp;
                $date = $tmp['date'];
            }
            $list["date"] = $date;
            $list["value"] = $list1;
            $mine[] = $list;
            $data ['status'] = self::API_OK;
            $data ['detail'] = $mine;

            $data ['pageCount'] = isset ( $page ) ? $page : '0';
            $data ['totalPage'] = isset ( $dataProvider->pagination->pageCount ) ? $dataProvider->pagination->pageCount : '0';
		} else {
			$data ['error'] = 'No Custom Job Found';
		}
		return $this->sendResponse ( $data );
	}

	public function actionCancelTask($page = null) {
		$data = [ ];
		$list = [ ];
		$user = \yii::$app->user->id;
		$query = Job::find ()->where ( [ 
				'created_by_id' => $user 
		] )->andFilterWhere ( [ 
				'in',
				'state_id',
				[ 
						Job::STATE_CANCEL,
						Job::STATE_DISPUTE 
				] 
		] );
		$dataProvider = new \yii\data\ActiveDataProvider ( [ 
				'query' => $query,
				'pagination' => [ 
						'pageSize' => '10',
						'page' => $page 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				
				$data ['list'] [] = $model->asJson ();
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = 'No Custom Job Found';
		}
		return $this->sendResponse ( $data );
	}

	public function actionSearchCategory($id) {
		$data = [ ];
		$list = [ ];
		// $params = \Yii::$app->request->bodyParams;
		
		$category = Category::find ()->where ( [ 
				'id' => $id 
		] )->one ();
		$user = \yii::$app->user->id;
		if (! empty ( $category )) {
			if ($category->type_id == Category::LOCAL_SERVICE) {
				
				$lat = \yii::$app->user->identity->latitude;
				$longt = \yii::$app->user->identity->longitude;
				if (! empty ( $lat ) && ! empty ( $longt )) {
					$jobs = Job::find ()->select ( "*,( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) -
					radians({$longt}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) AS distance" )->having ( "distance <:distance" )->addParams ( [ 
							':distance' => '40' 
					] )->where ( [ 
							'category_id' => $id,
							'job_type' => Job::JOB_TYPE_CUSTOM 
					] )->andWhere ( [ 
							'!=',
							'created_by_id',
							$user 
					] )->andWhere ( [ 
							'!=',
							'worker_id',
							$user 
					] )->andFilterWhere ( [ 
							'!=',
							'state_id',
							Job::STATE_AWARDED 
					] )->orderBy ( 'id ASC' )->all ();
				}
			} else {
				$jobs = Job::find ()->where ( [ 
						'category_id' => $id 
				] )->andFilterWhere ( [ 
						'!=',
						'created_by_id',
						\yii::$app->user->id 
				] )->andWhere ( [ 
						'!=',
						'worker_id',
						$user 
				] )->andFilterWhere ( [ 
						'!=',
						'state_id',
						Job::STATE_AWARDED 
				] )->orderBy ( 'id ASC' )->all ();
			}
		}
		if (! empty ( $jobs )) {
			$list = [ ];
			foreach ( $jobs as $job ) {
				
				$list [] = $job->asJson ( false, $id );
			}
			$data ['status'] = self::API_OK;
			$data ['list'] = $list;
		} else {
			$data ['error'] = 'Nothing Found';
		}
		return $this->sendResponse ( $data );
	}
	
	public function actionFilterWorkerLocal($page = null, $lat, $long) {
		$data = [ ];
		$searchModel = new \app\models\search\Job();
		$params = \Yii::$app->request->bodyParams;
		/* $job = Job::find ()->where ( [
				'worker_id' => \yii::$app->user->id
		] )->one (); */
		
		$dataProvider = $searchModel->searchWorkerLocal ( \yii::$app->request->bodyParams, $page, $lat, $long );
		
		if ($dataProvider->models) {
			$data ['pageSize'] = $dataProvider->pagination->pageSize;
			$data ['pageCount'] = $dataProvider->pagination->pageCount;
			
			foreach ( $dataProvider->models as $model ) {
				$list [] = $model->asSearch ();
				
				$data ['list'] = $list;
			}
			$data ['status'] = self::API_OK;
		} else {
			$data ['error'] = "Worker Not Found";
		}
		
		return $this->sendResponse ( $data );
	}


	public function actionBidTimeCheck() {
        $params = \Yii::$app->request->bodyParams;
        $job_id = $params['job_id'];
        $time = date_create($params['time']);

        $job = Job::find()->where([
            'id' => $job_id
        ])->one();

        $jobTime = date_create($job->created_on);

        $jobTime = date_add($jobTime, date_interval_create_from_date_string('4 days'));
        $diff = $time->diff($jobTime);

        $data = array();

        if($diff) {
            if((int)$diff->format('%R%a')  >= 0 )
            {
                $data ['status'] = self::API_OK;
                $data ['detail'] = array();
                $data ['detail']['isover'] = 0;
                $data ['detail']['Y'] = $diff->format("%Y");
                $data ['detail']['m'] = $diff->format("%m");
                $data ['detail']['d'] = $diff->format("%d");
                $data ['detail']['H'] = $diff->format("%H");
                $data ['detail']['i'] = $diff->format("%i");
                $data ['detail']['s'] = $diff->format("%s");
            }else{
                $data ['status'] = self::API_OK;
                $data ['detail'] = array();
                $data ['detail']['isover'] = 1;
            }
        }else{
            $data ['status'] = "Time diff error";
        }

        return $this->sendResponse ( $data );
    }

    public function actionJobRemoteComplete($job_id) {
        $job = Job::find()->where([
            'id' => $job_id
        ])->one();

        $job->state_id = Job::STATE_COMPLETE;

        $data = array();

        if ($job->save ()) {
            $data['status'] = self::API_OK;
            $data['message'] = "Your job successfully finished.";
        }else{
            $data ['status'] = "Time difference error!";
        }

        return $this->sendResponse ( $data );
    }
}