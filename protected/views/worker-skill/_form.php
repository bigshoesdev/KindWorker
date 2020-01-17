<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\select2\Select2;
use app\models\SubCategory;

/* @var $this yii\web\View */
/* @var $model app\models\WorkerSkill */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'worker-skill-form',
//                        'enableClientValidation' => true,
//                        'enableAjaxValidation' => false
						]);
?>



         <?php echo $form->field ( $model, 'sub_category_id' )->widget ( Select2::classname (), [ 'data' => \yii\helpers\ArrayHelper::Map (SubCategory::find ()->all (), 'id', 'title' ),'language' => 'en','options' => [ 'placeholder' => 'Select subcategory' ],'pluginOptions' => [ 'allowClear' => true,'multiple' => false ] ] );?>



		 <?php echo $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); ?>
	 		


		 <?php echo $form->field($model, 'hourly_rate')->textInput(['maxlength' => 256]) ?>
	 		


		 <?php echo $form->field($model, 'experience')->textInput() ?>
	 		


		 <?php echo $form->field($model, 'delivery_time')->textInput(['maxlength' => 256]) ?>
	 		


		 <?php /* echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
	 		


		 <?php /* echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'worker-skill-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
