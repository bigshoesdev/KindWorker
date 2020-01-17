<?php

use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\Message */

/*$this->title =  $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div
			class="message-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'message-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'message:html',
            'session_id',
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
            [
			'attribute' => 'booking_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('booking_id'),
			],
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            [
			'attribute' => 'to_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('to_id'),
			],
            'send_time:datetime',
            [
			'attribute' => 'from_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('from_id'),
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
	</div>
</div>