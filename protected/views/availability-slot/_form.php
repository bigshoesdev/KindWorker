<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AvailabilitySlot */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'availability-slot-form',
						]);
?>
	



		
		 <?php echo $form->field($model, 'service_id')->textInput() ?>
	 		

		
		 <?php echo $form->field($model, 'from_time')->widget(yii\jui\DatePicker::className(),
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			//'minDate' => 0,
			'changeMonth' => true,'changeYear' => true ] ]) ?>
	 		

		
		 <?php echo $form->field($model, 'to_time')->widget(yii\jui\DatePicker::className(),
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			//'minDate' => 0,
			'changeMonth' => true,'changeYear' => true ] ]) ?>
	 		

		
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
