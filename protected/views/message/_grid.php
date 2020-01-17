<?php

use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Message $searchModel
 */

?>
<?php Pjax::begin(); ?>
    <?php echo TGridView::widget([
    	'id' => 'message-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            /* 'message:html',*/
            'session_id',
            [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],
            [
				'attribute' => 'booking_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('booking_id');  },
				],
            ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],
            [
				'attribute' => 'to_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('to_id');  },
				],
            /* 'send_time:datetime',*/
            [
				'attribute' => 'from_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('from_id');  },
				],
            /* 'created_on:datetime',*/
            /* 'updated_on:datetime',*/
            /* [
				'attribute' => 'created_by_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },
				],*/

            ['class' => 'app\components\TActionColumn','header'=>'<a>Actions</a>'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

