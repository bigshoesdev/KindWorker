<?php
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\WorkerSkill */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'Worker Skills' ),
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = ( string ) $model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div class="worker-skill-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php
echo \app\components\TDetailView::widget ( [ 
						'id' => 'worker-skill-detail-view',
						'model' => $model,
						'options' => [ 
								'class' => 'table table-bordered' 
						],
						'attributes' => [ 
								'id',
								 [ 
										'attribute' => 'sub_category_id',
										'label' => 'Sub Category',
										'value' => 
											isset ( $model->subCategory->title ) ? $model->subCategory->title : ''
									
								],
								
								'hourly_rate',
								'experience',
								'delivery_time',
								'created_on',
								'updated_on',
								[ 
										'attribute' => 'created_by_id',
										'label' => 'Created By',
										'value' => 
											isset ( $model->createdBy->first_name ) ? $model->createdBy->first_name : ''
									
								] ,
                                'description'
						] 
				] );			
						?>


<?php // echo $model->description;?>

 			<div>
		<?php
		
echo UserAction::widget ( [ 
				'model' => $model,
				'attribute' => 'state_id',
				'states' => $model->getStateOptions () 
		] );
		?>

		</div>
		</div>
	</div>
</div>
