<?php
use app\models\User;
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
?>

<div class="well main_wrapper">

<div class="container">
		<div class="row">
			<section class="section col-md-6">
		 <div class="text-left sec-title-padding padd-left">
		  <h1 class="cnt-top text-left ">Register Here </h1>
		 		 <div class="headul"></div>
						</div>
	<div class="content-bottom">

	                                <h4>
             <span>   
   
    Everything is customizable. Colors, fonts, headers...</span></h4>
            <p></p><p><span style="background-color:rgb(245, 245, 245); color:rgb(85, 85, 85); font-family:open sans,sans-serif; font-size:16px">We leverage technology to accomplish great vision.

 
</span></p>
<p></p>
                      </div>

	<div class="content-bottom">

	                  <h4>
            <span>  Highly Flexible ,always growing , Easy Management</span></h4>
            <p></p><p><span style="background-color:rgb(245, 245, 245); color:rgb(85, 85, 85); font-family:open sans,sans-serif; font-size:16px"> Support and free updates forever.</span></p>
<p></p>
            
          </div>
          <div class="content-bottom">
           
          </div>
		</section>
			<div class="col-sm-12 col-md-6">
            <div class="form-outer padd-lt">

			
			<?php
		
		$form = TActiveForm::begin ( [ 
				'id' => 'form-signup',
				'options' => [ 
						'class' => 'driver-form form-horizontal' 
				] 
		] );
		?>
			<h3 class="text-center mar-tp-11">Registration Form</h3>
				

					<div class="row">
						<div class="col-sm-12">
							
						</div>
						<?= $form->field($model, 'first_name',['template'=>'<div class="col-sm-12">{input}{error}</div>'])
						->textInput(['maxlength' => true,'placeholder'=>'First Name'])
						->label(false)?>
						<?= $form->field($model, 'last_name',['template'=>'<div class="col-sm-12">{input}{error}</div>'])
						->textInput(['maxlength' => true,'placeholder'=>'Last Name'])
						->label(false)?>
						
							
						
						<?= $form->field($model, 'email',['template'=>'<div class="col-sm-12">{input}{error}</div>'])
						->textInput(['maxlength' => true,'placeholder'=>'Email'])
						->label(false)?>
						
							<?= $form->field($model, 'password',['template'=>'<div class="col-sm-12">{input}{error}</div>'])
						->passwordInput(['maxlength' => true,'placeholder'=>'Password'])
						->label(false)?>
						
							
							<?= $form->field($model, 'confirm_password',['template'=>'<div class="col-sm-12">{input}{error}</div>'])
						->passwordInput(['maxlength' => true,'placeholder'=>'Confirm Password'])
						->label(false)?>
						<div class="form-group">
						
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-lg btn-success btn-block',
                    		'name' => 'signup-button'])
                    
                    		
                    ?>
                </div>


		<div class="registration m-t-20 m-b-20">
			<a class="" href="<?php echo Url::toRoute(['user/login']);?>"> Login
			</a>
		</div>

						
					<?php TActiveForm::end(); ?>				<!-- driver form ends -->



		</div>

	</div>
</div>
</div>
</div>
</div>
