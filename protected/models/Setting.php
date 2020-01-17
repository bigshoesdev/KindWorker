<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_setting".
 *
 * @property integer $id
 * @property string $key
 * @property string $type_id
 * @property string $value
 * @property string $default
 *
 */
namespace app\models;

use Yii;
use yii\components;

class Setting extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->key;
	}
	public static function getTypeOptions() {
		return [ 
				'object',
				'boolean',
				'bool',
				'integer',
				'int',
				'float',
				'string',
				'array' 
		];
	}
	public function getType() {
		$list = self::getTypeOptions ();
		return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%setting}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'key',
								'value' 
						],
						'required' 
				],
				[ 
						[ 
								'key',
								'type_id',
								'value',
								'default' 
						],
						'string',
						'max' => 255 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'key' => Yii::t ( 'app', 'Key' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'value' => Yii::t ( 'app', 'Value' ),
				'default' => Yii::t ( 'app', 'Default' ) 
		];
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		return $relations;
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['key'] = $this->key;
		$json ['type_id'] = $this->type_id;
		$json ['value'] = $this->value;
		$json ['default'] = $this->default;
		if ($with_relations) {
		}
		return $json;
	}
	public function getSettings() {
		$settings = static::find ()->where ( [ 
				'active' => true 
		] )->asArray ()->all ();
	}
	public function setSetting($key, $value, $type = null) {
		$model = static::findOne ( [ 
				'key' => $key 
		] );
		if ($model === null) {
			$model = new static ();
			$model->active = 1;
		}
		
		$model->key = $key;
		$model->value = strval ( $value );
		if ($type !== null) {
			$model->type = $type;
		} else {
			$t = gettype ( $value );
			if ($t == 'string') {
				$error = false;
				try {
					Json::decode ( $value );
				} catch ( InvalidParamException $e ) {
					$error = true;
				}
				if (! $error) {
					$t = 'object';
				}
			}
			$model->type = $t;
		}
		return $model->save ();
	}
	public function activateSetting($value, $key) {
		$model = static::findOne ( [ 
				'value' => $value,
				'key' => $key 
		] );
		if ($model && $model->active == 0) {
			$model->active = 1;
			return $model->save ();
		}
		return false;
	}
	public function deactivateSetting($value, $key) {
		$model = static::findOne ( [ 
				'value' => $value,
				'key' => $key 
		] );
		if ($model && $model->active == 1) {
			$model->active = 0;
			return $model->save ();
		}
		return false;
	}
	public function deleteSetting($value, $key) {
		$model = static::findOne ( [ 
				'value' => $value,
				'key' => $key 
		] );
		if ($model) {
			return $model->delete ();
		}
		return true;
	}
	public function deleteAllSettings() {
		return static::deleteAll ();
	}
	public function findSetting($key, $value = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key, 2 );
			if (count ( $pieces ) > 1) {
				$value = $pieces [0];
				$key = $pieces [1];
			} else {
				$value = '';
			}
		}
		return $this->find ()->where ( [ 
				'value' => $value,
				'key' => $key 
		] )->limit ( 1 )->one ();
	}
	/* public function beforeSave($insert) {
	 $validators = $this->getType ( false );
	 if (! array_key_exists ( $this->type, $validators )) {
	 $this->addError ( 'type', Module::t ( 'settings', 'Please select correct type' ) );
	 return false;
	 }
	 $model = DynamicModel::validateData ( [
	 'value' => $this->value
	 ], [
	 $validators [$this->type]
	 ] );
	 if ($model->hasErrors ()) {
	 $this->addError ( 'value', $model->getFirstError ( 'value' ) );
	 return false;
	 }
	 if ($this->hasErrors ()) {
	 return false;
	 }
	 return parent::beforeSave ( $insert );
	 } */
}