<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$Link = $user->getLoginUrl();
?>
<div class="password-reset">
	<p>Hello <?php echo  Html::encode($user->full_name) ?>,</p> Your credentials for Login into site of <?php echo Yii::$app->name ?> is
<p>
	Thank you for registering with <?php echo Yii::$app->name ?>.
	Your login Credentials are
	<br>
	Email: <?php echo $user->email;?>
	<br>
	<?php
			$password = User::randomPassword();

			$user->setPassword($password, $password);
			echo 'Password:-'.$password;?>
	<br>
	

    <p>Follow the link :</p>

	<p><?= Html::a(Html::encode($Link), $Link) ?></p>
</div>
