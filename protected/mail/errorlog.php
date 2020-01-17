<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<div class="password-reset">
	<h3>Hi Admin</h3>
	<p>There was an error/crash reported in <?= \Yii::$app->name; ?>!</p>
    <br>
    <p>Details are as follows:</p>
    <br>
    <p>Time: <?= $user->create_time; ?></p>
    <p>Error Code: HTTP-<?= $user->error; ?></p>
    <p>Url: <?= isset($user->api) ? $user->api : 'Not Reported'; ?></p>
    <br>
    <p><?= \Yii::$app->name; ?> Team</p>
    <p style="margin: 0 auto;">&copy; <?= date("Y") ?> <?= \Yii::$app->name; ?> | All Rights Reserved</p>
</div>