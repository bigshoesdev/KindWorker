<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_user_portfolio".
 *
 * @property integer $id
 * @property string $image_file
 * @property string $title
 * @property string $description
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property Category $category
 * @property User $createdBy
 */
namespace app\models;

use Yii;

class UserPortfolio extends \app\components\TActiveRecord {
    public function __toString() {
        return ( string ) $this->age;
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
        return '{{%user_portfolio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'created_on',
                    'updated_on' ,
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'description',
                    'image_file',
                ],
                'string',
                'max' => 255
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
                    'image_file'
                ],
                'trim'
            ],
            [
                [
                    'image_file'
                ],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg,jpeg'
            ]
        ];
    }

    /**                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             4
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t ( 'app', 'ID' ),
            'image_file' => Yii::t ( 'app', 'Image File' ),
            'title' => Yii::t ( 'app', 'Title' ),
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

    public function asJson() {
        $json = [ ];
        $json ['id'] = $this->id;
        $json ['title'] = $this->title;
        $json ['description'] = $this->description;
        if (isset( $this->image_file)) {
            $json['image_file'] = \Yii::$app->urlManager->createAbsoluteUrl ( [
                'user/download/',
                'profile_file' => $this->image_file
            ] );
        } else {
            $json['image_file']='';
        }
        $json ['created_on'] = $this->created_on;
        $json ['updated_on'] = $this->updated_on;
        $json ['created_by_id'] = $this->created_by_id;
        return $json;
    }
}
