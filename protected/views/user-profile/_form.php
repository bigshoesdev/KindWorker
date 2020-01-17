<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'user-profile-form',
						]);
?>
	




<div class="col-md-6">
	
			
		 <?php /*echo $form->field($model, 'age')->textInput() */ ?>
	 		

		
		 <?php //echo $form->field($model, 'category_id')->textInput() ?>
	 		

		
		 <?php /*echo $form->field($model, 'height')->textInput(['maxlength' => 255]) */ ?>
	 		

		
		 <?php //echo $form->field($model, 'skills')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php echo $form->field($model, 'document_file')->fileInput() ?>
	 		

	</div>
	<div class="col-md-6">

				
		 <?php //echo $form->field($model, 'education_qualification')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php /*echo $form->field($model, 'experience')->textInput(['maxlength' => 255]) */ ?>
	 		

		
		 <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 			</div>
	
	


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
