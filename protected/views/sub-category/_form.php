<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\select2\Select2;
use app\models\Category;
/* @var $this yii\web\View */
/* @var $model app\models\SubCategory */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
						'layout' => 'horizontal',
						'id'	=> 'sub-category-form',
						]);
?>
	
        <?=$form->field ( $model, 'category_id' )->widget ( Select2::classname (), [ 'data' => \yii\helpers\ArrayHelper::Map ( Category::find ()->all (), 'id', 'title' ),'language' => 'en','options' => [ 'placeholder' => 'Select category' ],'pluginOptions' => [ 'allowClear' => true,'multiple' => false ] ] );?>


		 <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
	 		


	   <div class="form-group">
		<div id="load" class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>