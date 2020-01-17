<?php
/**
 * Created by PhpStorm.
 * User: hys
 * Date: 11/23/2017
 * Time: 12:34 PM
 */
/**
 * This is the model class for table "tbl_payment".
 *
 * @property integer $id
 * @property string $transaction_id
 * @property string $payment_mode
 * @property string $currency
 * @property string $amount
 * @property int $model_id
 * @property string $model_type
 * @property int $state_id
 * @property int $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id ===Relative data ===
 *
 * @property Comment[] $comments
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Payment extends \app\components\TActiveRecord {

    const STATE_INACTIVE = 0;
    const STATE_ACTIVE = 1;
    const STATE_DELETED = 2;

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
        return '{{%payment}}';
    }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'transaction_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'model_id',
                    'state_id',
                    'type_id',
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
                    'transaction_id',
                    'payment_mode',
                    'model_type',
                    'currency',
                    'amount'
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
            'transaction_id' => Yii::t ( 'app', 'Transaction ID' ),
            'payment_mode' => Yii::t ( 'app', 'Payment_mode' ),
            'currency' => Yii::t ( 'app', 'Currency' ),
            'amount' => Yii::t ( 'app', 'Amount' ),
            'model_type' => Yii::t ( 'app', 'Model Type' ),
            'model_id' => Yii::t ( 'app', 'Model ID' ),
            'state_id' => Yii::t ( 'app', 'Sate_id' ),
            'type_id' => Yii::t ( 'app', 'Type_id' ),
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

}