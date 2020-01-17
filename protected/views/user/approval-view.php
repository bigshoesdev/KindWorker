<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\comment\CommentsWidget;
use app\components\PageHeader;
use app\components\TDetailView;
use app\components\useraction\UserAction;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

// $this->title = $model->id;

$this->params ['breadcrumbs'] [] = [ 
		'label' => 'User',
		'url' => [ 
				'user/index' 
		] 
];
$this->params ['breadcrumbs'] [] = [ 
		'label' => $model->first_name
];
?>

<div class="wrapper">
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


	<div class=" panel ">
		<?php echo  PageHeader::widget(['title'=>$model->first_name,'model'=>$model]); ?>
	</div>
	<div class=" panel ">
		<div class=" panel-body ">	
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
												'contact_no',
												'message',
										    
												[  
														'attribute' => 'role_id',
														'visible' => ($model->role_id== User::ROLE_CUSTOMER ||$model->role_id== User::ROLE_WORKER ||$model->role_id== User::ROLE_SUBADMIN) ? true : false,
														'format' => 'raw',
														'value' => $model->getRoleOptions ( $model->role_id ) 
												],
												[
														'attribute' => 'status',
													//	'visible' => ($model->role_id== User::ROLE_CUSTOMER ||$model->role_id== User::ROLE_WORKER ) ? true : false,
														//'format' => 'raw',
														'value' => $model->getStatusOptions ( $model->status )
												],
												
												'created_on:datetime' 
										] 
								] )?>
		</div>
		</div>
		<div>
				<?php
				echo UserAction::widget ( [ 
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions () 
				] );
				?>	
			</div>



	</div>

	<div class=" panel ">
		 
				<?php echo  CommentsWidget::widget(['model'=>$model]); ?>	
		 
	</div>
</div>

