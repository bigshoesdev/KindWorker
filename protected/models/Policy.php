<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_policy".
 *
 * @property integer $id
 * @property string $description
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Policy extends \app\components\TActiveRecord {

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
        return '{{%policy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'description',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'id',
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
            'description' => Yii::t ( 'app', 'Description' ),
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
        $json ['description'] = $this->description;
        //$json ['created_on'] = $this->created_on;
        //$json ['created_by_id'] = $this->created_by_id;
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