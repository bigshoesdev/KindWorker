<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
?>


<div class="panel-body">


    <?php $form = TActiveForm::begin(['id' => 'user-actions-form',]); ?>

	<div class="  btn-group  pull-right ">
	<?php
	
	foreach ( $allowed as $id => $act ) {
		
		if ($id != $model->{$attribute}) {
			
			echo '';
			echo Html::submitButton ( $act, array (
					'name' => 'workflow',
					'value' => $act,
					'class' => 'btn btn-success ' 
			) );
			echo '';
		}
	}
	
	?>
	</div>
<?php if(\Yii::$app->session->hasFlash('user-action')): ?>
	<div class="alert alert-info ">
		<div class="flash-success alert-link">
	<?php echo \Yii::$app->session->getFlash('user-action'); ?>
		</div>
	</div>
<?php endif;?>

<?php TActiveForm::end(); ?>


</div>