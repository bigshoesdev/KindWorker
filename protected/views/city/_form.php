<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\select2\Select2;
use app\models\State;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'city-form',
						]);
?>
	



		    <?=$form->field ( $model, 's_id' )->widget ( Select2::classname (), [ 'data' => \yii\helpers\ArrayHelper::Map ( State::find ()->all (), 'id', 'name' ),'language' => 'en','options' => [ 'placeholder' => 'Select state' ],'pluginOptions' => [ 'allowClear' => true,'multiple' => false ] ] );?>

		
		 <?php   echo $form->field($model, 'name')->textInput(['maxlength' => 30])  ?>
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
