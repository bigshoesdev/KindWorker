<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->title = Yii::t ( 'app', 'Add {modelClass}', [ 
		'modelClass' => 'User'
] );

$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="wrapper">
	<div class="user-create">


	<?=  \app\components\PageHeader::widget(['title'=>"Add Sub Admin"]); ?>
</div>


	<div class="content-section clearfix">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

