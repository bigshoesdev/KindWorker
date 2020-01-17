<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use app\components\TDetailView;
use app\models\User;

use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('UserUpdate')): 
?>

<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('UserUpdate')?>
</div>
<?php endif; ?>

<div class="panel-body">
<div ng-app="" >
		  <div class="col-md-12">
		<h3>User</h3>
		<div class="col-md-2">
			 <?php if(!empty($model->profile_file)){?>
				<?php echo Html::img(['user/download','profile_file'=>$model->profile_file],['class'=>'img-responsive']); ?>					
				         <?php
				} 

				else {
					?>
				         
				         
				         
				     <?php echo  Html::img($this->theme->getUrl('img/default.png'),['class'=>'img-responsive']) ;?>
				      <?php }?>
                    
            </div>
            <div  class="col-md-10">
   				 <?php
   				 
   				 
   				 
   				 
								echo TDetailView::widget ( [ 
										'model' => $model,
										'attributes' => [ 
												'id',
												'first_name',
												'last_name',
												'email:email',
										    
												[  
														'attribute' => 'role_id',
														'visible' => ($model->role_id== User::ROLE_CUSTOMER ||$model->role_id== User::ROLE_WORKER ||$model->role_id== User::ROLE_SUBADMIN ) ? true : false,
														'format' => 'raw',
														'value' => $model->getRoleOptions ( $model->role_id ) 
												],
										    'address',
										    'approval_address',
												'created_on:datetime' 
										] 
								] )?>
		</div>
		<div class="clearfix"></div>
		</div>
		  <div class="col-md-12">
		 <h3>User Profile</h3>
				<div class="col-md-2">
			 <?php
			 
			
			 
			 if(!empty($promodel->document_file)){?>
				<?php echo Html::img(['user/download','profile_file'=>$promodel->document_file],['class'=>'img-responsive']); ?>					
				         <?php
				} 

				else {
					?>
				         
				         
				         
				     <?php echo  Html::img($this->theme->getUrl('img/default.png'),['class'=>'img-responsive']) ;?>
				      <?php }?>
                    
            </div>
             
		         <div  class="col-md-10">
   				 <?php
   				 
   				 echo TDetailView::widget ( [
   				 		'model' => $promodel,
   				 		'attributes' => [
   				 				'id',
   				 				'age',
   				 				'skills',
   				 				'document_file',
   				 				'education_qualification',
   				 				'experience'
   				 				
   				 		]
   				 ] )
   				 
							?>
		</div>
		
		<div class="clearfix"></div>
		</div>
		  <div class="col-md-12">
		<h3>Bank Details</h3>
		    <div  class="col-md-12">
   				 <?php
   				 
   				 
   				 
   				 
								echo TDetailView::widget ( [ 
										'model' => $bankmodel,
										'attributes' => [ 
												'id',
												'bank_name',
												'account_no',
									
										] 
								] )?>
								
		</div>
		  </div>
    <?php
				
				$form = TActiveForm::begin ( [ 
						'layout' => 'horizontal',
						'id' => 'user-form',
						'enableClientValidation' => true 
				] );
				?>
	

    
   	 <?=$form->field($model, 'status')->dropDownList($model->getAdminStatusOptions(), ['prompt' => 'Choose your option '])?>
   	 
   <?= $form->field($model, 'message')->textInput(['ng-model' => "name",'maxlength' => 255])?>
 


 <h1>Admin Message  :-  >  {{name}}</h1>

 
    <div
		class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom">
		<div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success'])?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div></div>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>

