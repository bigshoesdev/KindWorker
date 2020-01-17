<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
/* @var $this yii\web\View */
/* @var $user common\models\User */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl ( [ 
		'site/reset-password',
		'token' => $user->password_reset_token 
] );
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink?>
