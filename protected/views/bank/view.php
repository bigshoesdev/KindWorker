<?php

use yii\helpers\Html;
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\Bank */

/*$this->title =  $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div
			class="bank-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'bank-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'bank_name',
            'account_no',
            'routing_no',
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            'created_on:datetime',
            'updated_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('created_by_id'),
			],
        ],
    ]) ?>


<?php  ?>

 			<div>
		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
		
 </div>

	</div>
	<div class=" panel ">
		<div class=" panel-body ">

<?php echo app\components\comment\CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>