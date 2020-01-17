<?php

namespace app\components;

use app\models\User;

class WebUser extends \yii\web\User {
	public $enableAutoLogin = true;
	public $identityClass = 'app\models\User';
	public $loginUrl = [ 
			'/user/login' 
	];
	public $authTimeout = 86400;
	public $identityCookie = [ 
			'name' => '_userBase',
			'path' => '/' 
	];
	public function afterLogin($identity, $cookieBased, $duration) {
		$identity->last_visit_time = date ( 'Y-m-d H:i:s' );
		$identity->save ();
		return parent::afterLogin ( $identity, $cookieBased, $duration );
	}
}
