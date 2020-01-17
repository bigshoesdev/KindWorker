<?php
use yii\helpers\Html;
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\State $searchModel
 */

?>
<?php Pjax::begin(['id'=>'stateGrid','enablePushState'=>isset($enablePushState)?$enablePushState:true]); ?>
    <?php
				
				echo TGridView::widget ( [ 
						'id' => 'state-ajax-grid-view',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'tableOptions' => [ 
								'class' => 'table table-bordered' 
						],
						'columns' => [ 
								// ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
								
								'id',
								'name',
								[ 
										'attribute' => 'country_id',
										'format' => 'raw',
										'value' => function ($data) {
											return $data->getRelatedDataLink ( 'country_id' );
										} 
								],
								[ 
										'attribute' => 'type_id',
										'filter' => isset ( $searchModel ) ? $searchModel->getTypeOptions () : null,
										'value' => function ($data) {
											return $data->getType ();
										} 
								],
								[ 
										'attribute' => 'state_id',
										'format' => 'raw',
										'filter' => isset ( $searchModel ) ? $searchModel->getStateOptions () : null,
										'value' => function ($data) {
											return $data->getStateBadge ();
										} 
								],
								[ 
										'attribute' => 'created_by_id',
										'format' => 'raw',
										'value' => function ($data) {
											return $data->getRelatedDataLink ( 'created_by_id' );
										} 
								],
								
								[ 
										'class' => 'app\components\TActionColumn',
										'template'=>'{view} {update}',
										'header' => '<a>Actions</a>' 
								] 
						] 
				] );
				?>
<?php Pjax::end(); ?>

