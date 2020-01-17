<?php

namespace app\components;

use yii\bootstrap\ActiveForm;

class TActiveForm extends ActiveForm {
	public $enableAjaxValidation = true;
	public $enableClientValidation = true;
	public $options = [ 
			'enctype' => 'multipart/form-data' 
	];
	public $fieldClass = 'app\components\TActiveField';
}
