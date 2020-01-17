<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel-body">

    <?php
				
				$form = TActiveForm::begin ( [ 
						'layout' => 'horizontal',
						'id' => 'user-form',
                        'enableClientValidation' => true,
	                	'enableAjaxValidation' => false
				] );
				?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 55])?>
     <?= $form->field($model, 'last_name')->textInput(['maxlength' => 55])?>


    <?= $form->field($model, 'email')->textInput(['maxlength' => 128])?>

    <?php if (Yii::$app->controller->action->id != 'update'){?>


     <?php }?>


    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => 15])?>

   <?= $form->field($model, 'profile_file')->fileInput()?>

    <?= $form->field($model, 'addPassword')->passwordInput(['maxlength' => 128])?>
    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => 128])?>


    <div id="load" class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom">
		<div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success'])?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>

