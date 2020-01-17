<?php
use app\components\useraction\UserAction;
use yii\widgets\Pjax;
use app\components\TGridView;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Job */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'Jobs' ),
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = ( string ) $model;
?>


<div class="wrapper">
	<div class=" panel ">

		<div class="job-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php
				
echo \app\components\TDetailView::widget ( [ 
						'id' => 'job-detail-view',
						'model' => $model,
						'options' => [ 
								'class' => 'table table-bordered' 
						],
						'attributes' => [ 
								'id',
								[ 
										'label' => 'Worker Name',
										'attribute' => 'worker_id',
										'visible' => !empty($model->worker_id),
										'format' => 'raw',
										'value' => isset ( $model->worker->first_name ) ? $model->worker->first_name : '' 
								],
								[
										
										'attribute' => 'title',
										'visible' => !empty($model->title),
										'value' => $model->title
								],
								[
										'label' => 'Category',
										'attribute' => 'category_id',
										'format' => 'raw',
										'value' => isset ( $model->category->title ) ? $model->category->title: ''
								],
								[ 'label' => 'Sub Category',
										'attribute' => 'sub_category_id',
										'format' => 'raw',
										'value' => isset ( $model->subCategory->title ) ? $model->subCategory->title: ''
								],
								[
										'header' => '<a>' . Yii::t ( 'app', 'Image' ) . '</a>',
										'attribute' => 'first_file',
										'format' => 'html',
										'filter' => false,
										'visible' => !empty($model->first_file),
										'value' => function ($model) {
										if (! empty ( $model->first_file)) {
											return Html::img ( [
													'/user/download',
													'profile_file' => @$model->first_file
											], [
													'height' => '50',
													'width' => '60'
											] );
										}
										?>
        		           <?php
										} 
								],
								[
										'header' => '<a>' . Yii::t ( 'app', 'Image' ) . '</a>',
										'attribute' => 'second_file',
										'format' => 'html',
										'filter' => false,
										'visible' => !empty($model->second_file),
										'value' => function ($model) {
										if (! empty ( $model->second_file)) {
											return Html::img ( [
													'/user/download',
													'profile_file' => @$model->second_file
											], [
													'height' => '50',
													'width' => '60'
											] );
										}
										?>
        		           <?php
										} 
								],
								[
										'header' => '<a>' . Yii::t ( 'app', 'Image' ) . '</a>',
										'attribute' => 'third_file',
										'format' => 'html',
										'filter' => false,
										'visible' => !empty($model->third_file),
										'value' => function ($model) {
										if (! empty ( $model->third_file)) {
											return Html::img ( [
													'/user/download',
													'profile_file' => @$model->third_file
											], [
													'height' => '50',
													'width' => '60'
											] );
										}
										?>
        		           <?php
										} 
								],
								[
										
										'attribute' => 'slot_id',
										'visible' => !empty($model->slot_id),
										'value' => $model->slot_id
								],
								'estimated_price',
								'address',
								[
										'attribute' => 'budget_type',
										'format' => 'raw',
										'value' => $model->getBudgetType($model->budget_type)
								],
								[
										
										'attribute' => 'date',
										'visible' => !empty($model->date),
										'value' => $model->date
								],
								[
										
										'attribute' => 'gig_quantity',
										'visible' => !empty($model->gig_quantity),
										'value' => $model->gig_quantity
								],
								[ 
										'attribute' => 'state_id',
										'format' => 'raw',
										'value' => $model->getState ($model->state_id) 
								],
								'created_on:datetime',
								[ 
										'attribute' => 'created_by_id',
										'format' => 'raw',
										'value' => isset ( $model->createdBy->first_name ) ? $model->createdBy->first_name: ''
								],
                                [
                                    'attribute' => 'description',
                                    'format' => 'raw',
                                    'value' => $model->description
                                ]

						] 
				] )?>

<!---->
<!--<p>--><?php
//
//echo HtmlPurifier::process ( $model->description );
//?><!--</p>-->
<!---->
   <?php Pjax::begin(); ?>
    <?php
				
echo TGridView::widget ( [ 
						'id' => 'job-image-grid-view',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'tableOptions' => [ 
								'class' => 'table table-bordered' 
						],
						'columns' => [ 
								'id',
								[ 
										'header' => '<a>' . Yii::t ( 'app', 'Image' ) . '</a>',
										'attribute' => 'image_file',
										'format' => 'html',
										'filter' => false,
										'value' => function ($data) {
											if (! empty ( $data->image_file )) {
												return Html::img ( [ 
														'/user/download',
														'profile_file' => @$data->image_file 
												], [ 
														'height' => '50',
														'width' => '60' 
												] );
											}
											?>
        		           <?php
										} 
								],
								[ 
										'label' => 'Type',
										'attribute' => 'type_id',
										'filter' => isset ( $searchModel ) ? $searchModel->getTypeOptions () : null,
										'value' => function ($data) {
											return $data->getType ( $data->type_id );
										} 
								],
								'created_on:datetime',
								[ 
										'class' => 'app\components\TActionColumn',
										'header' => '<a>Actions</a>',
										'template' => '{view}'
            		]
        ],
    ]); ?>
    <?php Pjax::end(); ?> 


		
		</div>
	</div>
</div>