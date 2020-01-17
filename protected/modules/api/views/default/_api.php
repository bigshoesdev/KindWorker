<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<table class="well table table-condensed">
<?php
foreach ( $model as $class => $apis ) {
	?>
<tr>
		<th class="text-center" colspan="4">
<?= $class;?>
</th>
	</tr>
	<tr>
		<th>API</th>
		<th>POST PARAMATERS</th>

		<th>Response</th>
	</tr>
<?php	foreach ($apis as $api=>$posts) {?>
<tr>
		<td>
<?php
		
		$api_action = preg_replace ( '/\?(.*)$/', '-', $api );
		
		echo 'api' . Html::textInput ( 'action_' . $class . '-' . $api_action, $class . '/' . $api, [ 
				'style' => 'width:50%' 
		] );
		?>

<?= '<br/>';?>
</td>
		<td><span class="btn btn-warning text-center retest"
			id="retest_<?=$class?>-<?= $api_action;?>"
			api-id="<?= $api_action;?>" class-id="<?= $class;?>">Retest</span> <a
			href='javascript:' class="show-button">Show/Hide</a>
			<form id="form_<?= $class?>-<?= $api_action;?>"
				action="<?= Url::toRoute([$class.'/'.$api])?>"
				enctype="multipart/form-data">
				<div class="field">
<?php
		foreach ( $posts as $param => $data ) {
			?><div class="col-md-4">
<?php
			echo $param;
			?></div>
					<div class="col-md-6" class="param">
			<?php
			
			if (strstr ( $param, '_file' ))
				echo Html::fileInput ( $param, $data, [ 
						'id' => 'param',
						'style' => 'width:60%;' 
				] );
			else
				echo Html::textInput ( $param, $data, [ 
						'id' => 'param',
						'style' => 'width:60%;' 
				] );
			
			echo '<br/>';
			?></div>
					<div class="col-md-2">
						<a id="remove" style="cursor: pointer;"><i class="fa fa-remove"></i></a>
					</div>
				</div>
				<div class="clearfix"></div>
			<?php
		}
		?>
</form></td>
		<td><span class="btn btn-primary"
			id="response_<?= $class?>-<?= $api_action?>">Pending</span></td>
	</tr>	
<?php
	}
}
?>
</table>