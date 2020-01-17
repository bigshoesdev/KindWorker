<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'comment')->textarea(['rows' => 2])->label(false) //$form->field($model, 'comment')->widget(letyii	inymce\Tinymce::className(),[]) //$form->field($model, 'comment')->widget(kartik\widgets\Html5Input::className(),[]) ?>

<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right post-comment' : 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>

