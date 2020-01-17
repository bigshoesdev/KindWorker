<?php
/**
 * Created by PhpStorm.
 * User: KNI
 * Date: 10/29/2017
 * Time: 4:15 PM
 */

use yii\helpers\Html;
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Budget $searchModel
 */

?>
<?php Pjax::begin(); ?>
<?php echo TGridView::widget([
    'id' => 'budget-grid-view',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions'=>['class'=>'table table-bordered'],
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

        'id',
        'title',
        //'description:html',
        [
            'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
            'value' => function ($data) { return $data->getState();  },
        ],
        'first_budget',
        'last_budget',
        /*    ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
           'value' => function ($data) { return $data->getType();  },], */
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
        [
            'attribute' => 'created_by_id',
            'format'=>'raw',
            'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },
        ],

        [
            'class' => 'app\components\TActionColumn',
            'header'=>'<a>Actions</a>',
            'template' =>  User::isAdmin ()?'{view} {update} {delete}':'{view}'
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
