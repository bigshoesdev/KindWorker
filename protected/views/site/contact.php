<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */
use app\components\TActiveForm;

/*
 * $this->title = 'Contact';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>
<div class="well main_wrapper">
<div class="container">
			<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')){ ?>
        	<div class="contact-success">Thank you for contacting us. We
		will respond to you as soon as possible.</div>
		
			<?php }else{ ?>
		<div class="row">
		<section class="col-md-6">
			<div class="text-left  padd-left">
				<h1 class="text-left ">Contact Us</h1>
			</div>
			<h4>
				<span> Everything is customizable. Colors, fonts, headers...</span>
			</h4>
			<p>
				<span
					style="background-color: rgb(245, 245, 245); color: rgb(85, 85, 85); font-family: open sans, sans-serif; font-size: 16px">We
					leverage technologyto accomplish great vision. </span>
			</p>
			<h4>
				<span> Highly Flexible ,always growing , Easy Management</span>
			</h4>
			<p>
				<span
					style="background-color: rgb(245, 245, 245); color: rgb(85, 85, 85); font-family: open sans, sans-serif; font-size: 16px">
					Support and free updates forever. </span>
			</p>
		</section>
		<div class="col-sm-12 col-md-6">
			<div class="form-outer padd-lt">
				<h3 class="text-center">Contact Form</h3>
			 <?php
				$form = TActiveForm::begin ( [ 
						'id' => 'contact-form',
						'options' => [ 
								'class' => 'driver-form form-horizontal' 
						],
						'fieldConfig' => [ 
								'template' => "{input}{error}" 
						] 
				] );
				?>
            		
                     <?php echo $form->field ( $model, 'name')->textInput ( [ 'placeholder' => 'Name' ] )->label ( false )?>
                     <?php echo  $form->field($model, 'email')->textInput(['placeholder'=>'Email'])->label(false)?>
                     <?php echo  $form->field($model, 'subject')->textInput(['placeholder'=>'Subject'])->label(false)?>
                     <?php echo $form->field ( $model, 'body' )->textArea ( [ 'rows' => 6,'placeholder' => 'Message' ] )->label ( false )?>
					<?php
				
				echo \yii\helpers\Html::submitButton ( 'Submit', [ 
						'class' => 'btn btn-lg btn-block btn-danger submit-btn btn btn-default',
						'name' => 'submit-button' 
				] )?>
	
                    <?php TActiveForm::end(); ?>                
				</div>
		</div>
	<?php }?>
	</div>
</div>
</div>
