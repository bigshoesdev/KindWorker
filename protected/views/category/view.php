<?php

use app\components\useraction\UserAction;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Category */

/*$this->title =  $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div
			class="category-view ">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>
<div class=" panel ">
<div class=" panel-body ">
	<div class ="col-md-2">
	
	<?php
				echo Html::img ( [ 
						'user/download',
						'profile_file' => $model->image_file 
				], [ 
						'height' => '150',
						'width' => '150' 
				] )?>
							
	
	</div>
	
	
	<div class=" col-md-10 ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'category-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
          
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            'created_on:datetime',
            'updated_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('created_by_id'),
			],
            //'description'
        ],
    ]) ?>

<?php  echo $model->description;?>

 </div>
 </div>
</div>

	<div class=" panel ">
				<div class=" panel-body ">
					<div
						class="category-panel">

<?php
$this->context->startPanel();
	$this->context->addPanel('SubCategories', 'subCategories', 'SubCategory',$model);
	//$this->context->addPanel('UserServices', 'userServices', 'UserService',$model);

$this->context->endPanel();
?>
</div>

</div>

<?php echo app\components\comment\CommentsWidget::widget(['model'=>$model]); ?>
			
			</div>
	
	 
	
