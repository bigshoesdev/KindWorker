<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = $user->getResetUrl ();
?>
<div class="password-reset">
	<p>Hello <?php echo  Html::encode($user->full_name) ?>,</p>
<?php //echo $resetLink;?>
    <p>Follow the link below to reset your password:</p>

	<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
