<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\WorkerAmount */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Amounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');;
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('worker-amount/add')): 
?>

<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('worker-amount/add')?>
</div>
<?php endif; ?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			<div class=" panel ">
				<div
					class="worker-amount-index">

<?=  \app\components\PageHeader::widget(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
			</div>
		</div>
		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix">
					<header class="panel-heading head-border">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
			</div>
		</div>
	</div>

</div>

