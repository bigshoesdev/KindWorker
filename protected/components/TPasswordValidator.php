<?php

namespace app\components;

use yii\validators\Validator;

class TPasswordValidator extends Validator {
	public $length = 8;
	
	public function validateAttribute($model, $attribute) {
		$pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';		
		// $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
		if ( strlen($model->$attribute) < $this->length)
			$model->addError ( $attribute, "Your password must be $this->length characters long." );
		if (! preg_match ( $pattern, $model->$attribute ))
			$model->addError ( $attribute, 'Your password is not strong enough!' );
	}
}