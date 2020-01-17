<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params ['breadcrumbs'] [] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>

		<div class="vd_title-section clearfix">
			<div class="vd_panel-header">
			<div class="page-head">
			<h1><?= Html::encode('Change password') ?></h1>
			</div>
			</div>
		</div>
	
	<div class="wrapper">
	<div class="panel">
	<header class="panel-heading"> Please fill out the following fields to
			change password : </header>
	<div class="panel-body change-pass-outer">	
<div class=" col-md-8 col-md-offset-2">
	<div class="site-changepassword">
	
  
    <?php
				
$form = ActiveForm::begin ( [ 
						'id' => 'changepassword-form',
						'options' => [ 
								'class' => 'form-horizontal' 
						],
						'fieldConfig' => [ 
								'template' => "{label}\n<div class=\"col-lg-8\">
                        {input}</div>\n<div class=\"col-md-6 col-md-offset-4\">
                        {error}</div>",
								'labelOptions' => [ 
										'class' => 'col-lg-4 control-label' 
								] 
						],
	//	'action'=>['api/user/change-password'],
				] );
				?>
            <?= $form->field($model,'newPassword',['inputOptions'=>[
            'placeholder'=>'','value'=>'']])->label()->passwordInput() ?>
         
       
        <?= $form->field($model,'confirm_password',['inputOptions'=>[
            'placeholder'=>'','value'=>'']])->label()->passwordInput() ?>
       
        <div class="form-group">
		<div class="col-lg-12 text-right">
                <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-primary' ] )?>
            </div>
	</div>
    <?php ActiveForm::end(); ?>
</div>
	
</div>
</div>
</div>
</div>

