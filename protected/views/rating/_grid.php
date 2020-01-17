<?php

use app\components\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Rating $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_rating-grid"])?>
<?php Pjax::begin(['id'=>'rating-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'rating-grid-view',
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
        		'rate',
        		'comment',
        		[
        				'label' => 'Job',
        				'attribute' => 'model_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->job->title ) ? $data->job->title : '';
        		}
        		],
        		[
        				'label' => 'Created for',
        				'attribute' => 'user_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->user->first_name ) ? $data->user->first_name : '';
        		}
        		],
        		[
        				'attribute' => 'state_id',
        				'format' => 'raw',
        				'filter' => isset ( $searchModel ) ? $searchModel->getStateOptions () : null,
        				'value' => function ($data) {
        				return $data->getStateBadge ();
        		}
        		],
        		[
        				'attribute' => 'type_id',
        				'filter' => isset ( $searchModel ) ? $searchModel->getTypeOptions () : null,
        				'value' => function ($data) {
        				return $data->getType ();
        		}
        		],
        		[
        				'label' => 'Created by',
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
$('#bulk_delete_rating-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#rating-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['rating/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#rating-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

