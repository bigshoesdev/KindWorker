<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_slot".
 *
 * @property integer $id
 * @property string $times
 * @property integer $day_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Slot extends \app\components\TActiveRecord {
    public function __toString() {
        return ( string ) $this->id;
    }
    const STATE_INACTIVE = 0;
    const STATE_ACTIVE = 1;
    const STATE_DELETED = 2;

    const DAY_MON = 0;
    const DAY_TUE = 1;
    const DAY_WED = 2;
    const DAY_THU = 3;
    const DAY_FRI = 4;
    const DAY_SAT = 5;
    const DAY_SUN = 6;

    const TYPE_FREE = 0;
    const TYPE_BOOK = 1;
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
            self::TYPE_FREE => "slot free",
            self::TYPE_BOOK => "slot book",
        ];
    }
    public function getType() {
        $list = self::getTypeOptions ();
        return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
    }
    public static function getDayIDOptions() {
        return [
            self::DAY_MON => "Monday",
            self::DAY_TUE => "Tuesday",
            self::DAY_WED => "Wednesday",
            self::DAY_THU => "Thursday",
            self::DAY_FRI => "Friday",
            self::DAY_SAT => "Saturday",
            self::DAY_SUN => "Sunday"
        ];
    }
    public function getDayID() {
        $list = self::getDayIDOptions ();
        return isset ( $list [$this->day_id] ) ? $list [$this->day_id] : 'Not Defined';
    }

    public function beforeValidate() {
        if ($this->isNewRecord) {
            if (! isset ( $this->create_time ))
                $this->create_time = date ( 'Y-m-d H:i:s' );
            if (! isset ( $this->update_time ))
                $this->update_time = date ( 'Y-m-d H:i:s' );
            if (! isset ( $this->created_by_id ))
                $this->created_by_id = Yii::$app->user->id;
        } else {
            $this->update_time = date ( 'Y-m-d H:i:s' );
        }
        return parent::beforeValidate ();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%slot}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'times'
                ],
                'required'
            ],
            [
                [
                    'times',
                    'create_time',
                    'update_time' ,
                    'title'
                ],
                'safe'
            ],
            [
                [
                    'day_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
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
                    'day_id'
                ],
                'in',
                'range' => array_keys ( self::getDayIDOptions() )
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
            'times' => Yii::t ( 'app', 'Availability Times' ),
            'state_id' => Yii::t ( 'app', 'State' ),
            'day_id' => Yii::t ( 'app', 'Day' ),
            'type_id' => Yii::t ( 'app', 'Type' ),
            'create_time' => Yii::t ( 'app', 'Create Time' ),
            'update_time' => Yii::t ( 'app', 'Update Time' ),
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
    public function asJson($with_relations = false) {
        $json = [ ];

        $json ['id'] = $this->id;
        $json ['title'] = $this->title;
        $json ['times'] = $this->times;
        $json ['day_id'] = $this->day_id;
        $json ['state_id'] = $this->state_id;
        $json ['type_id'] = $this->type_id;
        $json ['created_by_id'] = $this->created_by_id;

        return $json;
    }
}
