<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $model app\models\User */

/*
 * $this->title = Yii::t ( 'app', 'Update {modelClass}: ', [
 * 'modelClass' => 'User'
 * ] ) . ' ' . $model->id;
 */
$this->params ['breadcrumbs'] [] = [
		'label' => 'User',
		'url' => [
				'user/index'
		]
];
$this->params ['breadcrumbs'] [] = [
		'label' => $model->first_name
];
$this->params ['breadcrumbs'] [] = Yii::t ( 'app', 'Update' );
?>
<div class="wrapper">
	<div class=" panel ">
<?=  \app\components\PageHeader::widget(['title'=>$model->first_name,'model'=>$model,'showAdd'=>false]); ?>
</div>

	<div class="content-section clearfix panel">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

