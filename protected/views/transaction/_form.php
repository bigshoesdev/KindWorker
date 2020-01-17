<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'transaction-form',
						]);
?>
	




<div class="col-md-6">
	
			
		 <?php echo $form->field($model, 'transaction_num')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php /*echo $form->field($model, 'payment_mode')->textInput() */ ?>
	 		

		
		 <?php echo $form->field($model, 'currency')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php /*echo $form->field($model, 'payer_id')->textInput() */ ?>
	 		

		
		 <?php /*echo $form->field($model, 'reciever_id')->textInput() */ ?>
	 		

		
		 <?php echo $form->field($model, 'amount')->textInput(['maxlength' => 256]) ?>
	 		

	</div>
	<div class="col-md-6">

				
		 <?php /*echo $form->field($model, 'model_id')->textInput() */ ?>
	 		

		
		 <?php /*echo $form->field($model, 'model_type')->textInput(['maxlength' => 1255]) */ ?>
	 		

		
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'role_id')->textInput() ?>
	 			</div>
	
	


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
