<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'message-form',
						]);
?>





		 <?php echo  $form->field($model, 'message')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'message')->textarea(['rows' => 6]); ?>
	 		


		 <?php echo $form->field($model, 'session_id')->textInput(['maxlength' => 255]) ?>
	 		


		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		


		 <?php echo $form->field($model, 'booking_id')->textInput() ?>
	 		


		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		


		 <?php echo $form->field($model, 'to_id')->textInput() ?>
	 		


		 <?php /*echo $form->field($model, 'send_time')->widget(yii\jui\DatePicker::className(),
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			//'minDate' => 0,
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
	 		


		 <?php echo $form->field($model, 'from_id')->textInput() ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'message-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
