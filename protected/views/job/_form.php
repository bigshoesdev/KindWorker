<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Job */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'job-form',
						]);
?>




<div class="col-md-6">

	
		 <?php echo $form->field($model, 'worker_id')->textInput() ?>
	 		


		 <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); ?>
	 		


		 <?php /*echo $form->field($model, 'title')->textInput(['maxlength' => 512]) */ ?>
	 		


		 <?php echo $form->field($model, 'total_price')->textInput(['maxlength' => 255]) ?>
	 		


		 <?php echo $form->field($model, 'category_id')->textInput() ?>
	 		


		 <?php echo $form->field($model, 'sub_category_id')->textInput() ?>
	 		


		 <?php echo $form->field($model, 'slot_id')->textInput(['maxlength' => 255]) ?>
	 		


		 <?php /*echo $form->field($model, 'estimated_price')->textInput(['maxlength' => 100]) */ ?>
	 		


		 <?php /*echo $form->field($model, 'first_file')->fileInput() */ ?>
	 		


		 <?php /*echo $form->field($model, 'second_file')->fileInput() */ ?>
	 		

	</div>
	<div class="col-md-6">

		
		 <?php /*echo $form->field($model, 'third_file')->fileInput() */ ?>
	 		


		 <?php /*echo $form->field($model, 'address')->textInput(['maxlength' => 512]) */ ?>
	 		


		 <?php /*echo $form->field($model, 'latitude')->textInput(['maxlength' => 512]) */ ?>
	 		


		 <?php /*echo $form->field($model, 'longitude')->textInput(['maxlength' => 512]) */ ?>
	 		


		 <?php /*echo $form->field($model, 'budget_type')->textInput() */ ?>
	 		


		 <?php echo $form->field($model, 'date')->textInput(['maxlength' => 255]) ?>
	 		


		 <?php /*echo $form->field($model, 'gig_quantity')->textInput() */ ?>
	 		


		 <?php echo $form->field($model, 'status')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		


		 <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
	 		


		 <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
	 			</div>

	


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'job-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
