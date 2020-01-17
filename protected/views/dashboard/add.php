<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->title = Yii::t ( 'app', 'Add {modelClass}', [
		'modelClass' => 'Sub Admin'
] );

$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="wrapper">
    <div class="panel">
    <div class="user-create">
	    <?=  \app\components\PageHeader::widget(['title'=>$this->title]); ?>
    </div>
    </div>

	<div class="content-section clearfix panel">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

