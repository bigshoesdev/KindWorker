<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_favorites".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property string $created_on
 * @property integer $created_by_id
 * === Related data ===
 * @property Service[] $services
 * @property Category $category
 * @property User $createdBy
 * @property UserService[] $userServices
 */
namespace app\models;

namespace app\models;

use Yii;
use yii\components;
use app\models\Service;
use app\models\Category;
use app\models\User;
use app\models\UserService;

class Favorites extends \app\components\TActiveRecord {
    public function __toString() {
        return ( string ) $this->title;
    }

    public function beforeValidate() {
        if ($this->isNewRecord) {
            if (!isset ($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (!isset ($this->created_by_id))
                $this->created_by_id = Yii::$app->user->id;
        }
        return parent::beforeValidate ();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%favorites}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'worker_id',
                    'job_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'worker_id',
                    'job_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'worker_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className (),
                'targetAttribute' => [
                    'worker_id' => 'id'
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
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className (),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t ( 'app', 'ID' ),
            'worker_id' => Yii::t ( 'app', 'Worker' ),
            'job_id' => Yii::t ( 'app', 'Job' ),
            'created_on' => Yii::t ( 'app', 'Created On' ),
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
    public function getJob() {
        return $this->hasOne ( Job::className (), [
            'id' => 'job_id'
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
    public static function getHasOneRelations() {
        $relations = [ ];
        $relations ['worker_id'] = [
            'worker',
            'User',
            'id'
        ];
        $relations ['job_id'] = [
            'job',
            'Job',
            'id'
        ];
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

    public function asJson($with_relations = false) {
        $json = [ ];
        $json ['id'] = $this->id;
        $json ['worker_id'] = $this->worker_id;
        $json['worker_detail']=$this->getWorkerDetail();
        $json ['job_id'] = $this->job_id;
        $json ['job_detail'] = $this->getJobDetail();
        $json ['created_on'] = $this->created_on;
        $json ['created_by_id'] = $this->created_by_id;

        return $json;
    }
    public function getWorkerDetail() {
        $worker = User::find ()->where ( [
            'id' => $this->worker_id
        ] )->one ();
        if (! empty ( $worker )) {
            return $worker->asWorkerJson();
        }
        return '';
    }
    public function getJobDetail() {
        $job = Job::find ()->where ( [
            'id' => $this->job_id
        ] )->one ();
        if (! empty ( $job )) {
            return $job->asJson();
        }
        return '';
    }
}
