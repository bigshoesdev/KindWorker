<?php

use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\Commission */

/*$this->title =  $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Commissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div
			class="commission-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'commission-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'amount',
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
          
         //   'created_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => isset($model->createdBy->first_name)?$model->createdBy->first_name:'',
			],
        ],
    ]) ?>


<?php  ?>


		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
</div>
 
	<div class=" panel ">
		<div class=" panel-body ">

<?php echo app\components\comment\CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
