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
use app\components\TGridView;
use yii\widgets\Pjax;

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

<?php

if (Yii::$app->session->hasFlash ( 'UserUpdate' )) :
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
			<div class="col-md-12">
				<h3>User</h3>
				<div class="col-md-2">
			 <?php if(!empty($model->profile_file)){?>
				<?php echo Html::img(['user/download','profile_file'=>$model->profile_file],['class'=>'img-responsive']); ?>					
				         <?php
				} else {
					?>
				     <?php echo  Html::img($this->theme->getUrl('img/default.png'),['class'=>'img-responsive']) ;?>
				      <?php }?>
                    
            </div>
				<div class="col-md-10">
   				 <?php
								
echo TDetailView::widget ( [ 
										'model' => $model,
										'attributes' => [ 
												'id',
												'first_name',
												'last_name',
												'contact_no',
												'email:email',
												
												[ 
														'attribute' => 'role_id',
														'visible' => ($model->role_id == User::ROLE_CUSTOMER || $model->role_id == User::ROLE_WORKER || $model->role_id == User::ROLE_SUBADMIN) ? true : false,
														'format' => 'raw',
														'value' => $model->getRoleOptions ( $model->role_id ) 
												],
												
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
				
				if (! empty ( $promodel->document_file )) {
					?>
				<?php echo Html::img(['user/download','profile_file'=>$promodel->document_file],['class'=>'img-responsive']); ?>					
				         <?php
				} else {
					?><?php echo  Html::img($this->theme->getUrl('img/default.png'),['class'=>'img-responsive']) ;?>
				      <?php }?>
                    
            </div>

				<div class="col-md-10">
   				 <?php
								
								echo TDetailView::widget ( [ 
										'model' => $promodel,
										'attributes' => [ 
												'id',
												'document_file' 
										
										] 
								] )?>
								
		</div>

				<div class="clearfix"></div>
			</div>
			<div class="col-md-12">
				<h3>Bank Details</h3>
				<div class="col-md-12">
   				 <?php
								
								echo TDetailView::widget ( [ 
										'model' => $bankmodel,
										'attributes' => [ 
												'id',
												'bank_name',
												'account_no' 
										
										] 
								] )?>
								
		</div>
			</div>
			<div class="col-md-12">
				<h3>Worker skills</h3>
				<div class="col-md-12">
   				<?php Pjax::begin(); ?>
    <?php
				
echo TGridView::widget ( [ 
						'id' => 'worker-skill-grid-view',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'tableOptions' => [ 
								'class' => 'table table-bordered' 
						],
						'columns' => [ 
								'id',
								[ 
										'attribute' => 'sub_category_id',
										'label' => 'Sub Category',
										'value' => function ($data) {
											return isset ( $data->subCategory->title ) ? $data->subCategory->title : '';
										} 
								],
								'description:html',
								'hourly_rate',
								'experience',
								[ 
										'attribute' => 'created_by_id',
										'format' => 'raw',
										'value' => function ($data) {
											return isset ( $data->createdBy->first_name ) ? $data->createdBy->first_name : '';
										} 
								],
								[ 
										'class' => 'app\components\TActionColumn',
										'header' => '<a>Actions</a>',
										'template' => '{view}' 
								] 
						] 
				] );
				?>
<?php Pjax::end(); ?>
								
		</div>
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
</div>

