<?php

use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\WorkerSkill $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_worker-skill-grid"])?>
<?php Pjax::begin(); ?>
    <?php echo TGridView::widget([
    	'id' => 'worker-skill-grid-view',
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
        		'attribute' => 'sub_category_id',
        		'label'=>'Sub Category',
        		'value' => function ($data) { 	return isset($data->subCategory->title)?$data->subCategory->title:'';  },
        		],
            'description:html',
            'hourly_rate',
            'experience',
             [
				'attribute' => 'created_by_id',
				'format'=>'raw',
				'value' => function ($data) { return isset($data->createdBy->first_name)?$data->createdBy->first_name:'';  },
				],
            [
				'class' => 'yii\grid\ActionColumn',
				'header' => '<a>Actions</a>',
				'template' =>  User::isAdmin ()?'{view} {update} {delete}':'{view} {update}'
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?>

<script>
    $('#bulk_delete_worker-skill-grid').click(function(e) {
        e.preventDefault();
        var keys = $('#worker-skill-grid-view').yiiGridView('getSelectedRows');

        if ( keys != '' ) {
            var ok = confirm("Do you really want to delete these items?");

            if( ok ) {
                $.ajax({
                    url  : '<?php echo Url::toRoute(['worker-skill/mass','action'=>'delete','model'=>get_class($searchModel)])?>',
                    type : "POST",
                    data : {
                        ids : keys,
                    },
                    success : function( response ) {
                        if ( response.status == "OK" ) {
                            $.pjax.reload({container: '#worker-skill-grid-view'});
                        }
                    }
                });
            }
        } else {
            alert('Please select items to delete');
        }
    });

</script>
