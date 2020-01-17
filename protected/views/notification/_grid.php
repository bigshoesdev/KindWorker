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
 * @var app\models\search\Notification $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_notification-grid"])?>
<?php Pjax::begin(['id'=>'notification-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'notification-grid-view',
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
        		'message:html',
                        [
                                'label' => 'Username',
                                'attribute' => 'model_id',
                                'format'=>'raw',
                                'value' => function ($data) { return isset($data->modelUser->first_name)?$data->modelUser->first_name:'';  },
        				],
                'create_time:datetime',
        				[
        						'label' => 'Created By',
        				        'attribute' => 'created_by_id',
        						'format'=>'raw',
        						'value' => function ($data) { return isset($data->createdBy->first_name)?$data->createdBy->first_name:'';  },
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
$('#bulk_delete_notification-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#notification-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['notification/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#notification-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

