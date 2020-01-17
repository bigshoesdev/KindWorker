<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

use yii\helpers\Html;
use yii\helpers\Url;
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="page-title-container">
	<div class="page-title">

		<div class="container">
			<h1 class="entry-title"><?php echo $model->title;?></h1>
		</div>
	</div>

	
	
</div>
<section id="content">
	<div class="container">
		<div id="main">
		<div class="alert-wrapper">
	<?php if(Yii::$app->session->hasFlash('success')) {?>
	<div style="margin-top:18px;" class="alert alert-success fade in">
    <a title="close" aria-label="close" data-dismiss="alert" class="close" href="#"></a>
	<strong>Success!</strong> <?php echo Yii::$app->session->getFlash('success');?>
	</div>
	<?php }?>
	</div>
			<div class="blog-more-section">
		<?php if(!Yii::$app->user->isGuest) {
		
			echo Html::a('<i class="fa fa-pencil"></i>Update',Url::to(['/blog/update','id'=>$model->id]),[
					'class'=>'btn btn-success pull-right',
					'target'=>'_blank'
			]);
		 }?>
				<div class="more-discription">
				<div class="col-sm-12 row">
					<p><?php  echo $model->description;?> </p>
</div>
<div class="col-sm-2">
	<?php if(!empty($model->profile_file)){?>
			<?php echo Html::img(['user/thumb','file'=>$model->profile_file,null,'h'=>150,'w'=>150],['class'=>'img-responsive','id'=>'image-', 'alt'=>$model]);?>
			<?php }?>
</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
 $(window).load(function(){
/* 	 $(".events-outer-section").find(".col-md-8").addClass("row padd-left-15 col-sm-8"); */
	 $(".events-outer-section").find(".col-md-4").removeClass("row");
	 $("[id^=image-]").each(function(){	
		 $(this).attr('src',"<?php echo Url::to(['user/thumb'])?>?file="+$(this).attr('data-id')+"&h=150&w=150&rand="+Math.rand()+"]");
	 });
	 
 });
 $("[id^=delete-comment-]").on('click',function(){
	 var id = $(this).attr('id').split('delete-comment-')[1];b 
	$.ajax({
		url :"<?php echo Url::to(['comment/delete-ajax'])?>?id="+id,
		type:'POST',
		data:{'id':id},
		success:function(response) {
			if(response.status == 'OK')
				$("#comments").html(response.html);
			}
			});
	 
	 });
 </script>