<?php

use app\components\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Job $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_job-grid"])?>
<?php Pjax::begin(['id'=>'job-pjax-grid']); ?>

    <?php echo TGridView::widget([
    	'id' => 'job-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
           [ 
								'name' => 'check',
								'class' => 'yii\grid\CheckboxColumn',
								'visible' => User::isAdmin () 
						],

        		'id',
        		'title',
        		[
        				'attribute' => 'category_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->category->title ) ? $data->category->title : '';
        		}
        		],
        		[
        				'attribute' => 'sub_category_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->subCategory->title ) ? $data->subCategory->title : '';
        		}
        		],
        		'estimated_price',
        		[
        				'attribute' => 'state_id',
        				'filter' => isset ( $searchModel ) ? $searchModel->getStateOptions () : null,
        				'value' => function ($data) {
        				return $data->getState ( $data->state_id );
        		}
        		],
        		[
        				'attribute' => 'created_by_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->createdBy->first_name ) ? $data->createdBy->first_name : '';
        		}
        		],
        		[
        				'class' => 'app\components\TActionColumn',
        				'header' => '<a>Actions</a>',
                        'template' =>  User::isAdmin ()?'{view} {delete}':'{view}'
        				//'template' => '{view} {delete}'
        		]
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_job-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#job-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['job/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#job-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

