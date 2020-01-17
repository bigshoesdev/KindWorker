<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_budget".
 *
 * @property integer $id
 * @property string $title
 * @property integer $state_id
 * @property integer $first_budget
 * @property integer $last_budget
 * @property string $budget
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Budget extends \app\components\TActiveRecord {
    public function __toString() {
        return ( string ) $this->title;
    }
    const BUDGET_TYPE_HOURLY = 0;
    const BUDGET_TYPE_FIXED = 1;


    public static function getStateOptions() {
        return [
            self::BUDGET_TYPE_HOURLY => "Hourly",
            self::BUDGET_TYPE_FIXED => "Fixed"
        ];
    }

    public function getState() {
        $list = self::getStateOptions ();
        return isset ( $list [$this->state_id] ) ? $list [$this->state_id] : 'Not Defined';
    }
//    public function getStateBadge() {
//        $list = [
//            self::BUDGET_TYPE_HOURLY => "Hourly",
//            self::BUDGET_TYPE_FIXED => "Fixed"
//        ];
//        return isset ( $list [$this->state_id] ) ? \yii\helpers\Html::tag ( 'span', $this->getState (), [
//            'class' => 'label label-' . $list [$this->state_id]
//        ] ) : 'Not Defined';
//    }

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
        return '{{%budget}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'title',
                    'state_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'title',
                    //'description',
                    'state_id',
                    'first_budget',
                    //'last_budget',
                    //'budget',
                    //'type_id',
                    'created_on',
                    //'updated_on',
                    'created_by_id'
                ],
                'required'
            ],
//            [
//                [
//                    'description'
//                ],
//                'string'
//            ],
            [
                [
                    'state_id',
                    'first_budget',
                    'last_budget',
                    //'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    //'budget'
                ],
                'string',
                'max' => 256
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t ( 'app', 'ID' ),
            'title' => Yii::t ( 'app', 'Title' ),
            //'description' => Yii::t ( 'app', 'Description' ),
            'state_id' => Yii::t ( 'app', 'State' ),
            'first_budget' => Yii::t ( 'app', 'Min_Budget(USD)' ),
            'last_budget' => Yii::t ( 'app', 'Max_Budget(USD)' ),
            //'budget' => Yii::t ( 'app', 'Budget(USD)' ),
            //'type_id' => Yii::t ( 'app', 'Type' ),
            'created_on' => Yii::t ( 'app', 'Created On' ),
            'updated_on' => Yii::t ( 'app', 'Updated On' ),
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
        //$json ['description'] = $this->description;
        $json ['state_id'] = $this->state_id;
        $json ['first_budget'] = $this->first_budget;
        $json ['last_budget'] = $this->last_budget;
//        $json ['budget'] = $this->first_budget."-".$this->last_budget."($)";
        if ($with_relations) {
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
        }
        return $json;
    }
}