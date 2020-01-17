<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;
use yii\db\ActiveQuery;
use app\models\Comment;

/**
 * This is the generic model class
 */
class TActiveRecord extends ActiveRecord {
	public static function findActive($state_id = 1) {
		return Yii::createObject ( ActiveQuery::className (), [ 
				get_called_class () 
		] )->andWhere ( [ 
				'state_id' => $state_id 
		] );
	}
	public static function label($n = 1) {
		$className = Inflector::camel2words ( StringHelper::basename ( self::className () ) );
		if ($n == 2)
			return Inflector::pluralize ( $className );
		return $className;
	}
	public function __toString() {
		return $this->label ( 1 );
	}
	public function getStateBadge() {
		return '';
	}
	public static function getStateOptions() {
		return [ ];
	}
	public function isAllowed() {
		if (User::isAdmin () || User::isSubAdmin())
			return true;
		if ($this instanceof User) {
			if ($this->hasAttribute ( 'id' ))
				return ($this->id == Yii::$app->user->id);
		}
		if ($this->hasAttribute ( 'create_user_id' ))
			return ($this->create_user_id == Yii::$app->user->id);
		if ($this->hasAttribute ( 'user_id' ))
			return ($this->user_id == Yii::$app->user->id);
		return false;
	}
//    public function isSubAdminAllowed() {
//        if (User::isSubAdmin ())
//            return true;
//        if ($this instanceof User) {
//            if ($this->hasAttribute ( 'id' ))
//                return ($this->id == Yii::$app->user->id);
//        }
//        if ($this->hasAttribute ( 'create_user_id' ))
//            return ($this->create_user_id == Yii::$app->user->id);
//        if ($this->hasAttribute ( 'user_id' ))
//            return ($this->user_id == Yii::$app->user->id);
//        return false;
//    }

	public function saveUploadedFile($model, $attribute = 'image_file') {
		$uploaded_file = UploadedFile::getInstance ( $model, $attribute );
		if ($uploaded_file != null) {
			if (($uploaded_file->extension == 'jpg' || $uploaded_file->extension == 'png' || $uploaded_file->extension == 'jpeg') && ($uploaded_file->size <= 2 * 1024 * 1024)) {
				$path = UPLOAD_PATH;
				$filename = $path . \yii::$app->controller->id . '-' . time () . '-' . $attribute . 'user_id_' . Yii::$app->user->id . '.' . $uploaded_file->extension;
				if (file_exists ( $filename ))
					unlink ( $filename );
				$uploaded_file->saveAs ( $filename );
				$model->$attribute = basename ( $filename );
				return true;
			} else {
				$error['error'] = 'Invalid File Extension.';
			}
		}
		return false;
	}
	public function updateHistory($comment) {
		$model = new Comment();
		$model->model_type = get_class ( $this );
		$model->model_id = $this->id;
		$model->comment = $comment;
		if ($model->save ())
			return true;
		return false;
	}
	public function getControllerID() {
		$modelClass = get_class ( $this );
		$pos = strrpos ( $modelClass, '\\' );
		$class = substr ( $modelClass, $pos + 1 );
		
		return Inflector::camel2id ( $class );
	}
	public function getUrl($action = 'view') {
		$params = [ 
				$this->getControllerID () . '/' . $action 
		];
		$params ['id'] = $this->id;
		
		// add the title parameter to the URL
		if ($this->hasAttribute ( 'title' ))
			$params ['title'] = $this->title;
		else
			$params ['title'] = ( string ) $this;
		
		return Yii::$app->getUrlManager ()->createAbsoluteUrl ( $params, true );
	}
	public function linkify($title = null, $controller = null, $action = 'view') {
		if ($title == null)
			$title = ( string ) $this;
		return Html::a ( $title, $this->getUrl ( $action, $controller ) );
	}
	public function getErrorsString() {
		$out = '';
		if ($this->errors != null)
			foreach ( $this->errors as $err ) {
				$out .= implode ( ',', $err );
			}
		return $out;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		return $relations;
	}
	public function getRelatedDataLink($key) {
		$hasOneRelations = $this->getHasOneRelations ();
		
		if (isset ( $hasOneRelations [$key] )) {
			$relation = $hasOneRelations [$key] [0];
			if (isset ( $this->$relation ))
				return $this->$relation->linkify ();
		}
		
		return $this->$key;
	}
	public static function deleteRelatedAll($query = []) {
		$models = self::find ()->where ( $query )->all ();
		foreach ( $models as $model ) {
			// Yii::log ( get_class ( $model ) . '-' . $model, CLogger::LEVEL_WARNING, '$model' );
			$model->delete ();
		}
	}
}
