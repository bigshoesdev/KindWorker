<?php

namespace app\components;

use yii\validators\Validator;

class TNameValidator extends Validator {
public $pattern = 	"/^[A-Za-z.]+((\s)?([A-Za-z])+)*$/";
	public function validateAttribute($model, $attribute) {
			if (! preg_match ( $this->pattern, $model->$attribute ))
				$model->addError ( $attribute,  $model->getAttributeLabel($attribute) . ' is invalid.');
	}
}