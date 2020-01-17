<?php

use yii\helpers\Html;
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\City $searchModel
 */

?>
<?php Pjax::begin(); ?>
    <?php echo TGridView::widget([
    	'id' => 'city-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
            'id',
            'name',
      /*       ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },], */


				['class' => 'app\components\TActionColumn','header'=>'<a>Actions</a>','template' => '{view} {update}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

