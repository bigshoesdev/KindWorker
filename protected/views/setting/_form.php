<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'setting-form',
						]);
?>
	



		
		 <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		

		
		 <?php echo $form->field($model, 'value')->textInput(['maxlength' => 255]) ?>
	 		

		
		 <?php echo $form->field($model, 'default')->textInput(['maxlength' => 255]) ?>
	 		


	   <div class="form-group">
		<div id="load" class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>