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
 * @var app\models\search\LoginHistory $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_login-history-grid"])?>
<?php Pjax::begin(['id'=>'login-history-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'login-history-grid-view',
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
        		/*          [
        		 'attribute' => 'name',
        		 'format'=>'raw',
        		 'value' => function ($data) { return $data->getRelatedDataLink('user_id');  },
        		 ], */
        		'user_ip',
        		'user_agent',
        		/* 'failer_reason',*/
        		[
        				'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
        				'value' => function ($data) { return $data->getStateBadge();  },],
        				
        				/* 'code',*/
        		// 'created_on:datetime',
        		[
        				'attribute' => 'created_on',
        				'filter' => \yii\jui\DatePicker::widget ( [
        						'model' => $searchModel,
        						'attribute' => 'created_on',
        						
        						'dateFormat' => 'yyyy-MM-dd',
        						'options' => [
        								'class' => 'form-control '
        						],
        						'clientOptions' => [
        								'changeMonth' => true,
        								'changeYear' => true
        						]
        				] )
        		],
        		
        		[
        		        'class' => 'app\components\TActionColumn',
                        //'template'=>'{view} {delete}',
                        'header'=>'<a>Actions</a>',
                        'template' =>  User::isAdmin ()?'{view} {delete}':'{view}'
                ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_login-history-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#login-history-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['login-history/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#login-history-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

