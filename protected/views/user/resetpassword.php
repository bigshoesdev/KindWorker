<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\TActiveForm;

//$this->title = 'Change Password';
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
<div class="container">
	<div class="login-box col-sm-4">
			<h3 class="colw text-center">
           <?= Html::encode($this->title) ?>
			</h3>

		<p class="text-center">Please fill out the following fields to change password :</p>
   
    <?php
				
				$form = TActiveForm::begin ( [ 
						'id' => 'changepassword-form',
						'options' => [ 
								'class' => 'form-horizontal' 
						],
						'fieldConfig' => [ 
								'template' => "{label}\n<div class=\"col-lg-9\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
								'labelOptions' => [ 
										'class' => 'col-lg-3 control-label' 
								] 
						] 
				] );
				?>
				
         <?=$form->field ( $model, 'password', [ 'inputOptions' => [ 'value' => '','placeholder' => '' ] ] )->passwordInput ()?>
          
      
        
       
        <div class="clearfix">
			<div class="col-lg-offset-4 col-lg-2">
                <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-primary' ] )?>
            </div>
		</div>
    <?php TActiveForm::end(); ?>
</div>
</div>