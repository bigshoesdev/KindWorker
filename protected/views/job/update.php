<?php


/* @var $this yii\web\View */
/* @var $model app\models\Job */

/* $this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Job',
]) . ' ' . $model->title; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class=" panel ">
		<div
			class="job-update">
	<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
	</div>
	</div>

	<div class="content-section clearfix panel">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

