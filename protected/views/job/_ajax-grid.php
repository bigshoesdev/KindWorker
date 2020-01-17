<?php

use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Job $searchModel
 */

?>
<?php Pjax::begin(); ?>
    <?php echo TGridView::widget([
    	'id' => 'job-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'worker_id',
            /* 'description:html',*/
            /* 'title',*/
            'total_price',
            [
				'attribute' => 'category_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('category_id');  },
				],
            [
				'attribute' => 'sub_category_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('sub_category_id');  },
				],
            'slot_id',
            /* 'estimated_price',*/
            /* ['attribute' => 'first_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->first_file);  },],*/
            /* ['attribute' => 'second_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->second_file);  },],*/
            /* ['attribute' => 'third_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->third_file);  },],*/
            /* 'address',*/
            /* 'latitude',*/
            /* 'longitude',*/
            /* 'budget_type',*/
            'date',
            /* 'gig_quantity',*/
            [
			'attribute' => 'status','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],
            /* [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],*/
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
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

