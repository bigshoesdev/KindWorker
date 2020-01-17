<?php

namespace app\components;

use Yii;
use app\models\Shadow;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

class ShadowWidget extends TBaseWidget {
	public function run() {
		if (\Yii::$app->user->isGuest)
			return true;
			$currentuser = Yii::$app->user->identity;
			$id = Yii::$app->session->get ( "shadow" );
			
			$shadow = Shadow::find ()->where ( [
					// 'id' =>$id,
					'to_id' => Yii::$app->user->id,
					'state_id' => Shadow::STATE_ACTIVE
			] )->one ();
			
			if ($shadow != null) {
				$this->renderContent ( $shadow, $currentuser );
			}
	}
	protected function renderContent($shadow,$currentuser) {

		echo Html::beginTag ( 'div', [
				'class' => 'alert-wrapper' 
		] );
		echo Html::beginTag ( 'div', [
				'class' => 'alert alert-danger' 
		] );
		echo 'You are logged in as ' . $currentuser->full_name . '(' . $currentuser->getRoleOptions ( $currentuser->role_id ) . ') To Login as Admin click ' . Html::a ( 'here', Url::to ( [ 
				'shadow/logout',
				'id' => $shadow->id 
		] ) ) . ' <i class="fa fa-hand-o-left"></i>';
		echo Html::endTag('div');
		echo Html::endTag('div');
		echo '<div class="clearfix"></div><br/>';
	}
}
