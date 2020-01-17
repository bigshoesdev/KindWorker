<?php
/**
 * Created by PhpStorm.
 * User: KNI
 * Date: 10/29/2017
 * Time: 4:15 PM
 */

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="panel-body">

    <?php
    $form = TActiveForm::begin([
        'layout' => 'horizontal',
        'id'	=> 'budget-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'=>false
    ]);
    ?>





    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>


    <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>

    <?php echo $form->field($model, 'first_budget')->textInput(['maxlength' => 128]) ?>

    <?php echo $form->field($model, 'last_budget')->textInput(['maxlength' => 128]) ?>

<!--    --><?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>


    <div class="form-group">
        <div id="load" class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php TActiveForm::end(); ?>

</div>
<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>