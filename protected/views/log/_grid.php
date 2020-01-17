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
 * @var app\models\search\Log $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_log-grid"])?>
<?php Pjax::begin(['id'=>'log-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'log-grid-view',
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
        		//  'error',
        		/* 'api:html',*/
        		/* 'description:html',*/
        		[
        				'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
        				'value' => function ($data) { return $data->getStateBadge();  },],
        				'link',
        				/*   ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
        				 'value' => function ($data) { return $data->getType();  },], */
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
        		
        		['class' => 'app\components\TActionColumn','header'=>'<a>Actions</a>','template' =>  User::isAdmin ()?'{view} {delete}':'{view}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_log-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#log-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['log/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#log-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

