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
 * @var app\models\search\Category $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_category-grid"])?>
<?php Pjax::begin(['id'=>'category-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'category-grid-view',
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
        		/* 'description:html',*/
        		/* ['attribute' => 'image_file','filter'=>$searchModel->getFileOptions(),
        		 'value' => function ($data) { return $data->getFileOptions($data->image_file);  },],*/
        		/* [
        		 'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
        		 'value' => function ($data) { return $data->getStateBadge();  },],*/
        		['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
        				'value' => function ($data) { return $data->getType();  },],
        				//'created_on:datetime',
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
        		/* 'updated_on:datetime',*/
        				/*        [
        				 'attribute' => 'created_by_id',
        				 'format'=>'raw',
        				 'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },
        				 ], */
        		
        		[
        		        'class' => 'app\components\TActionColumn',
                        'header'=>'<a>Actions</a>',
                        'template' =>  User::isAdmin ()?'{view} {update} {delete}':'{view} {update}'
                ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_category-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#category-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['category/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#category-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

