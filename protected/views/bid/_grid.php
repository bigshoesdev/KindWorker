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
 * @var app\models\search\Bid $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_bid-grid"])?>
<?php Pjax::begin(['id'=>'bid-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'bid-grid-view',
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
        		[
        				'attribute' => 'job_id',
        				'format' => 'raw',
        				'value' => function ($data) {
        				return isset ( $data->job->title ) ? $data->job->title : '';
        		}
        		],
        		'bid_price',
        		'created_on:datetime',
        		[ 'label' => 'Worker Name',
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
        				//'template' => '{view}{delete}'
        		],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_bid-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#bid-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['bid/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#bid-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

