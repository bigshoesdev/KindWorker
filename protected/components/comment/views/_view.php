
<?php
use yii\helpers\Html;
?>


<div class="items">
	<li>
		<div class="menu-icon">
	<?php echo Html::img(['/user/download','profile_file'=>Yii::$app->user->identity->profile_file],['height'=>'50','width'=>'50'])?>
		</div>
		<div class="menu-text">
			<p><?php echo \yii\helpers\Html::encode($model->comment)?></p>
			<ul class="nav" style="display: inline;"></ul>
		</div>
		<div class="menu-text">
			<div class="menu-info">
				<a href="#"></a><?= $model->createdBy->linkify()?> - <span
					class="menu-date"><?= \yii::$app->formatter->asDatetime($model->created_on)?> </span>
			</div>
		</div>

	</li>
	<div class="clearfix"></div>


</div>


