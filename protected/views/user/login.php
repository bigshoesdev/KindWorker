<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [ 
		'options' => [ 
				'class' => 'form-group has-feedback' 
		],
		
		'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>" 
];

$fieldOptions2 = [ 
		'options' => [ 
				'class' => 'form-group has-feedback' 
		],
		'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>" 
];
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<div class="well main_wrapper">
	<div class="container">
		
		<div class="login-box col-sm-4">
			<div class="text-center">
				<a class="page-logo" href="/">
					<h3 class="colw">Log In</h3>
				</a>
			</div>
			
			 <?php
				
				$form = TActiveForm::begin ( [ 
						'id' => 'login-form',
						'enableClientValidation' => false,
						'enableAjaxValidation' => false,
						'options' => [ 
								'class' => 'login-form form' 
						] 
				] );
            ?>
			 <?=$form->field ( $model, 'username', $fieldOptions1 )->label ( false )->textInput ( [ 'placeholder' => $model->getAttributeLabel ( 'email id' ) ] )?>
			<?=$form->field ( $model, 'password', $fieldOptions2 )->label ( false )->passwordInput ( [ 'placeholder' => $model->getAttributeLabel ( 'password' ) ] )?>

			<div class="row">
				<div class="col-md-6 padd-0">
					<div class="checkbox remember">
					<?php echo $form->field($model, 'rememberMe')->checkbox();?>
					
					</div>
				</div>
				<div class="col-md-6">
					<a class="forgot pull-right"
						href="<?php echo Url::toRoute(['user/recover'])?>">Forgot
						Password? </a>
				</div>

			</div>
<?=Html::submitButton ( 'Login', [ 'class' => 'btn btn-lg btn-block btn-success submit-btn btn btn-default','id' => 'login','name' => 'login-button' ] )?>
							<h4 class="text-center dont-text">
				  <a href="<?//php echo Url::to(['user/signup']);?>"></a>
			</h4>
			<?php TActiveForm::end()?>
		</div>
	</div>
</div>



























































