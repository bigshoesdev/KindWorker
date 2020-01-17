
function startPopUp() {
	$('#ajax_modal').hide();
	loading('remove_post_modal');
	$('#post_Modal').modal('show');
}

function insertInPopUp(html) {

	$('#ajax_modal').html(html).show();
	$('#remove_post_modal').hide();
}

function loading(id, center) {
	var src = '<?php echo Yii::app()->theme->baseUrl?>/images/ajax.gif';

	if (center == true) {
		var img = '<div class=""><img src="' + src
				+ '" alt ="loading..." / ></div>'
	} else {
		var img = '<div class="center_loading"><img src="' + src
				+ '" alt ="loading..." / ></div>'
	}
	$('#' + id).show()
	$('#' + id).html(img);
}