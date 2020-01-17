<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'service-form',
						]);
?>
	




<div class="col-md-6">
	
			
		 <?php echo $form->field($model, 'service_type')->textInput() ?>
	 		

		
		 <?php echo $form->field($model, 'category_id')->textInput() ?>
	 		

		
		 <?php echo $form->field($model, 'sub_category_id')->textInput() ?>
	 		

		
		 <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); //$form->field($model, 'description')->widget(kartik\widgets\Html5Input::className(),[]); ?>
	 		

		
		 <?php echo $form->field($model, 'rate_type')->textInput() ?>
	 		

	</div>
	<div class="col-md-6">

				
		 <?php echo $form->field($model, 'price')->textInput(['maxlength' => 256]) ?>
	 		
	 		

		
		 <?php echo $form->field($model, 'zipcode')->textInput(['maxlength' => 256]) ?>
	 		

		
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 			</div>
	
	


	   <div class="form-group">
		<div id="load" class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>