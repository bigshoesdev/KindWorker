 <?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use yii\base\Object;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

//$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="well main_wrapper">
	<div class="container">
		
		<div class="login-box col-sm-4">


  <?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success') ?>
</div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error') ?>
</div>
<?php endif; ?>

<h3 class="colw text-center"><?= Html::encode($this->title) ?></h3>


    
    <p class="text-center white">Please fill out your email. A link to reset password will be sent there.</p>
   
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email')->label(false)->textInput(['placeholder'=>'Email']) ?>


                <div class="form-group">
                
                    <?= Html::submitButton('Send', ['id'=>'send-button','class' => 'btn btn-primary btn-block']) ?>
               
                </div>
</div>
</div>
<?php ActiveForm::end(); ?>
</div>
 
