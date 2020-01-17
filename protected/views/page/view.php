<?php

use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'Pages' ),
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = ( string ) $model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div class="page-view ">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php
				
				echo \app\components\TDetailView::widget ( [ 
						'id' => 'page-detail-view',
						'model' => $model,
						'options' => [ 
								'class' => 'table table-bordered' 
						],
						'attributes' => [ 
								'id',
								'title',
								'description:html',
								
             [ 
										'attribute' => 'state_id',
										'format' => 'raw',
										'value' => $model->getStateBadge () 
								],
							/* 	[ 
										'attribute' => 'type_id',
										'value' => $model->getType () 
								], */
								'created_on:datetime',
								'updated_on:datetime',
								[ 
										'attribute' => 'created_by_id',
										'format' => 'raw',
										'value' => $model->getRelatedDataLink ( 'created_by_id' ) 
								] 
						] 
				] )?>


 

 			<div>
 

		</div>
		</div>
	</div>
	 

<?php echo app\components\comment\CommentsWidget::widget(['model'=>$model]); ?>
			</div>
 