<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\select2\Select2;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .select_worker {
        background: #0576ba none repeat scroll 0 0;
        border-radius: 5px;
        padding: 10px;
    }
    .select_worker a{
        color: #fff;
    }
</style>

<div ng-app="" class="panel-body">

    <?php

    $form = TActiveForm::begin([
						'layout' => 'horizontal' ,
    		'enableClientValidation' => true ,
    		'enableAjaxValidation' => false ,
    		'id' => 'user-form',

						]);
?>
<div id="customer" class="main_customer"><span class="select_customer"><a href="javascript:;">Select Customer:</a></span> </div>

    <div id ="show-customer" style="display:none;"  >
        <?php echo $form->field ( $model, 'customer' )->widget ( Select2::classname (), [ 'data' => \yii\helpers\ArrayHelper::Map ( User::find ()->select('id,first_name')->where(['in','role_id',User::ROLE_CUSTOMER])-> all(), 'id', 'first_name' ),'language' => 'en','options' => [ 'placeholder' => 'Select' ],'pluginOptions' => [ 'allowClear' => true,'multiple' => true ] ] )->label('Customer:');?>
            <?= $form->field($model, 'customer_message')->textInput(['ng-model'=>"customer",'required'=>true,'maxlength' => 150])?>
        <h1>Message:- {{Customer}}</h1>
    </div>

	   <div class="form-group">
		<div id="load" class="col-md-6 col-md-offset-3 bottom-admin-customer-button btn-space-bottom text-right" style="display:none">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send Notification') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>


<div ng-app="" class="panel-body">

    <?php

    $form = TActiveForm::begin([
        'layout' => 'horizontal' ,
        'enableClientValidation' => true ,
        'enableAjaxValidation' => false ,
        'id' => 'user-form',

    ]);
    ?>
    <div id="worker" class="main_worker"><span class="select_worker"><a href="javascript:;">Select Worker:</a></span> </div>

    <div id ="show-worker" style="display:none;"  >
        <?php echo $form->field ( $model, 'worker' )->widget ( Select2::classname (), [ 'data' => \yii\helpers\ArrayHelper::Map ( User::find ()->select('id,first_name')->where(['in','role_id',User::ROLE_WORKER])-> all(), 'id', 'first_name' ),'language' => 'en','options' => [ 'placeholder' => 'Select' ],'pluginOptions' => [ 'allowClear' => true,'multiple' => true ] ] )->label('Worker:');?>
        <?= $form->field($model, 'worker_message')->textInput(['ng-model'=>"worker",'required'=>true,'maxlength' => 150])?>
        <h1>Message:- {{Worker}}</h1>
    </div>

        <div class="form-group">
        <div id="load" class="col-md-6 col-md-offset-3 bottom-admin-worker-button btn-space-bottom text-right" style="display:none">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send Notification') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php TActiveForm::end(); ?>

</div>

<div class="loader-bg" style="display:none"></div>
<div class="loader" style="display:none"></div>
<script>
$(document).ready(function(){
	 $("#worker").click(function(){
	        $("#show-worker").show();
	        $("#show-customer").hide();
	        $(".bottom-admin-customer-button").hide();
            $(".bottom-admin-worker-button").show();
	    });
	    $("#customer").click(function(){
	    	 $("#show-worker").hide();
		        $("#show-customer").show();
		        $(".bottom-admin-worker-button").hide();
                $(".bottom-admin-customer-button").show();
	    });
	    $('#load').on('click', function() {
	    	   $(".loader-bg").show();
	    	    $(".loader").show();


	    	    setTimeout(function () {
	    	    	$(".loader-bg").hide();
	    	        $(".loader").hide();
	    	    }, 1000);
	    	    
	    	});
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>