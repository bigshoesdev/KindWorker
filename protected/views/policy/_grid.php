<?php

use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\User;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\WorkerSkill $searchModel
 */

?>
<?php Pjax::begin(); ?>
<?php echo TGridView::widget([
    'id' => 'worker-policy-grid-view',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions'=>['class'=>'table table-bordered'],
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

        'id',
        'description:html',
        [
            'attribute' => 'created_by_id',
            'format'=>'raw',
            'value' => function ($data) { return isset($data->createdBy->first_name)?$data->createdBy->first_name:'';  },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '<a>Actions</a>',
            'template' =>  User::isAdmin ()? '{view} {update}':'{view} {update}'
        ]
    ],
]); ?>
<?php Pjax::end(); ?>