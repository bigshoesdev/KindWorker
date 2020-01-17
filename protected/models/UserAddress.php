<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_user_address".
 *
 * @property integer $id
 * @property string $address
 * @property string $country
 * @property string $state
 * @property string $country
 * @property string $zipcode
 * @property string $latitude
 * @property string $longitude
 * @property integer $type
 * @property string $created_on
 * @property integer $created_by_id
 * === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\Service;
use app\models\Category;
use app\models\User;
use app\models\UserService;

class UserAddress extends \app\components\TActiveRecord {
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
        return '{{%user_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'address',
                    'country',
                    'state',
                    'city',
                    'zipcode',
                    'latitude',
                    'longitude',
                    'type',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'type',
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
            'address' => Yii::t ( 'app', 'Address' ),
            'country' => Yii::t ( 'app', 'Country' ),
            'state' => Yii::t ( 'app', 'State' ),
            'zipcode' => Yii::t ( 'app', 'Zip Code' ),
            'latitude' => Yii::t ( 'app', 'Latitude' ),
            'longitude' => Yii::t ( 'app', 'Longitude' ),
            'type' => Yii::t ( 'app', 'Type' ),
            'created_on' => Yii::t ( 'app', 'Created On' ),
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
        $json ['address'] = $this->address;
        $json ['country'] = $this->country;
        $json ['state'] = $this->state;
        $json ['city'] = $this->city;
        $json ['zipcode'] = $this->zipcode;
        $json ['latitude'] = $this->latitude;
        $json ['longitude'] = $this->longitude;
        $json ['type'] = $this->type;
        $json ['created_on'] = $this->created_on;
        $json ['created_by_id'] = $this->created_by_id;

        return $json;
    }

}
