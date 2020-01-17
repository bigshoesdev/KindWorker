<?php
use app\models\User;
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $this->title = Yii::t ( 'app', 'Users' );
$title="Users";

if( \Yii::$app->controller->action->id == 'approval-index' ) {
 $title='Approved Users';
 {$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Approved Users' 
]
;
}
}

if( \Yii::$app->controller->action->id == 'banned-index' ) {
	$title='Banned Users';
 {
	
	$this->params ['breadcrumbs'] [] = [
			'label' => 'Banned Users'
	]
	;
}
}
if( \Yii::$app->controller->action->id == 'deny-index' ) {
	$title='Denied Users';
	
	$this->params ['breadcrumbs'] [] = [
			'label' => 'Denied Users'
	]
	;
}

?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			<div class=" panel ">
						
			<?=  \app\components\PageHeader::widget(['title'=>$title]); ?>
</div>
		</div>
		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix">
				        <header class="panel-heading head-border">   <?php echo 'INDEX'; ?> </header>
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>

			</div>
		</div>
		
	</div>
</div>

