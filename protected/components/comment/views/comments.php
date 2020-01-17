
<div class="panel widget comment-view">
	<div class="panel-heading vd_bg-yellow">
		<h3 class="panel-title">
			<span> </span> Comments
		</h3>
	</div>
	<div class="panel-body-list">
<?php if ($model &&  !Yii::$app->user->isGuest) {?>
<?=$this->render ( '_form', [ 'model' => $model ] )?>

    <?php }?>
    		<div class="content-list content-image menu-action-right">
			<ul class="list-wrapper">
			
<?php
echo \yii\widgets\ListView::widget ( [ 
		'dataProvider' => $comments,
		
		'summary' => false,
		
		'itemOptions' => [ 
				'class' => 'item' 
		],
		'itemView' => '_view',
		'options' => [ 
				'class' => 'list-view comment-list' 
		] 
] );
?>
</ul>

		</div>
	</div>
</div>

