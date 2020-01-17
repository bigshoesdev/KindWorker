<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\widgets\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Slot */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'slot-form',
						]);
?>
	

		 <?php echo $form->field($model, 'from')->widget(kartik\widgets\TimePicker::className(),[]) ?>
	 		

		
		 <?php echo $form->field($model, 'to')->widget(kartik\widgets\TimePicker::className(),[]) ?>



	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
