<?php

use yii\helpers\Html;
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\State $searchModel
 */

?>
<?php Pjax::begin(); ?>
    <?php echo TGridView::widget([
    	'id' => 'state-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
            'id',
            'name',
            [
				'attribute' => 'country_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('country_id');  },
				],
       /*      ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },], */
            [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],
       ['class' => 'app\components\TActionColumn','header'=>'<a>Actions</a>',
            		'template'=> '{view}{update}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

