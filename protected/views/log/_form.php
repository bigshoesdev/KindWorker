<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'log-form',
						]);
?>
	



		
		 <?php echo $form->field($model, 'error')->textInput(['maxlength' => 256]) ?>
	 		

		
		 <?php /*echo  $form->field($model, 'api')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'api')->textarea(['rows' => 6]); //$form->field($model, 'api')->widget(kartik\widgets\Html5Input::className(),[]); */ ?>
	 		

		
		 <?php /*echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); //$form->field($model, 'description')->widget(kartik\widgets\Html5Input::className(),[]); */ ?>
	 		

		
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
