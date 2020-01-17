<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TGridView;
use app\models\User;
use yii\helpers\Html;

use yii\widgets\Pjax;

Pjax::begin ();

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */

?>
<div class="table table-responsive">
	 <?php
		
echo TGridView::widget ( [ 
				'id' => 'user-grid',
				'dataProvider' => $dataProvider,
	           	'enableRowClick' => false,
				'filterModel' => $searchModel,
				'columns' => [ 
						'id',
						'first_name',
						'last_name',
						'email:email',
						'contact_no',
						[ 
								'attribute' => 'state_id',
								'filter' => $searchModel->getStateOptions (),
								'format' => 'html',
								'value' => function ($data) {
									return $data->getStateBadge ();
								} 
						],
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
						[
								'class' => 'yii\grid\ActionColumn',
								'header' => '<a>Actions</a>',
								
								'template' =>  '{view}',
								'buttons' => [
										'update' => function ($url, $model, $key) {
										return Html::a ( '<span class="glyphicon glyphicon-eye-open" ></span>', [
												'/user/update-approval',
												'id' => $model->id
										] );
										
						}
						]
						
						]
						
				] 
		] );
		?>
</div>

