<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\widgets\ListView;
use yii\base\Widget;
use yii\helpers\Url;
?>

<div class='well'>

	<div class='api-list'>
<?php
echo ListView::widget ( [ 
		'dataProvider' => $dataProvider,
		'itemView' => '_api' 
] );
?>
</div>
</div>
<script>
$("[id^=retest]").click(function(e){
	//startPopUp();
	var api = $(this).attr('api-id');	
	var classid = $(this).attr('class-id');	
	var action = $("[name=action_"+classid+"-"+api+"]").val();	
	
	var values = $("#form_"+classid+"-"+api).serializeArray();
	var fd = new FormData();
	var file = $("#form_"+classid+"-"+api).find('input[type="file"]');
	
	if(typeof file[0] != 'undefined') { 
		var file_data = file[0].files; // for multiple files
		var name = $("#form_"+classid+"-"+api).find('input[type="file"]').attr('name');
		fd.append(name, file_data[0]);
	}
    $.each(values,function(key,input){
        fd.append(input.name,input.value);
    });
    
   
	$.ajax({
		url:"<?= Url::to('')?>"+action,
		type:'POST',
		data:fd,
		contentType:false,
		processData:false,
		success:function(response) {	
			console.log('res'+response);
			//alert(JSON.stringify(response));
			//insertInPopUp(JSON.stringify(response));
			if(response.status == '200') 
			{
				$("span#response_"+classid+'-'+api).removeClass('btn-primary').addClass('btn-success');
			}
			$("#response_"+classid+'-'+api).html(response.status);
		},
		error:function(xhr, status, error)
		{
			//var err = eval("(" + xhr.responseText + ")");			
			$("span#response_"+classid+'-'+api).html(error);
		}
		});
	
});
$(window).load(function(){
	/* 	$('[id^=retest]').each(function(){
			$(this).trigger('click');
		}); */
	
});
$("[id^=remove]").click(function(){
	$(this).parent().find('#param').attr('disabled',true);
	$(this).parent().parent().hide();	
	
});

$('div.api-list form').hide();
$('a.show-button').click(function(){
	$(this).parent().find( "form" ).toggle();
});
</script>