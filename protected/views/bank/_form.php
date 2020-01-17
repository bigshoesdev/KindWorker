<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bank */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'bank-form',
						]);
?>
	



		
		 <?php echo $form->field($model, 'bank_name')->textInput(['maxlength' => 256]) ?>
	 		

		
		 <?php echo $form->field($model, 'account_no')->textInput(['maxlength' => 256]) ?>
	 		

		
		 <?php echo $form->field($model, 'routing_no')->textInput(['maxlength' => 256]) ?>
	 		

		
		 <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
	 		

		
		 <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
