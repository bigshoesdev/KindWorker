<?php
use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use app\components\ShadowWidget;
use yii\widgets\Breadcrumbs;

$user = Yii::$app->user->identity;

/* @var $this \yii\web\View */
/* @var $content string */
// $this->title = yii::$app->name;

AppAsset::register ( $this );
?>
<?php

$this->beginPage ()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
<meta charset="<?=Yii::$app->charset?>" />
    <?=Html::csrfMetaTags ()?>
    <title><?=Html::encode ( $this->title )?></title>
    <?php
				
				$this->head ()?>
    <meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<link rel="shortcut icon"
	href="<?=$this->theme->getUrl ( 'img/favicon.ico' )?>"
	type="image/ico">

<link rel="canonical" href="<?php echo Url::canonical();?>">
<!--common style-->
<link
	href="<?php
	
	echo $this->theme->getUrl ( 'css/style-admin.css' )?>"
	rel="stylesheet">


<link
	href="<?php
	
	echo $this->theme->getUrl ( 'css/style-responsive.css' )?>"
	rel="stylesheet">
<!--theme color layout-->
<link
	href="<?php
	
	echo $this->theme->getUrl ( 'css/layout-theme-two.css' )?>"
	rel="stylesheet">
	
		<link
	href="<?php echo $this->theme->getUrl('js/jquery-toast/jquery.toast.min.css')?>"
	rel="stylesheet">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="sticky-header">
<?php

$this->beginBody ()?>

    <section>
		<!-- sidebar left start-->
		<div class="sidebar-left">
			<!--responsive view logo start-->
			<div class="logo theme-logo-bg visible-xs-* visible-sm-*">
				<a href="<?php
				
				echo Url::home ();
				?>"> <span class="brand-name"><?=Html::encode ( \yii::$app->name )?></span>
				</a>
			</div>
			<!--responsive view logo end-->

			<div class="sidebar-left-info">
				<!-- visible small devices start-->
				<div class=" search-field"></div>
				<!-- visible small devices end-->

				<!--sidebar nav start-->
		<?php

		if (method_exists ( $this->context, 'renderNav' )) {
			?>
			<?= Menu::widget ( [ 'encodeLabels' => false,'activateParents' => true,'items' => $this->context->renderNav (),'options' => [ 'class' => 'nav  nav-stacked side-navigation' ],'submenuTemplate' => "\n<ul class='child-list'>\n{items}\n</ul>\n" ] );?>
	<?php
		}
		?>
		<!--sidebar nav end-->

			</div>

		</div>
		<!-- sidebar left end-->

		<!-- body content start-->
		<div class="body-content">

			<!-- header section start-->
			<div class="header-section bg-danger light-color">

				<!--logo and logo icon start-->
				<div class="logo theme-logo-bg hidden-xs hidden-sm">
					<a href="<?php
					
					echo Url::home ();
					?>"> <!--<i class="fa fa-maxcdn"></i>--> <span class="brand-name"><?=Html::encode ( \yii::$app->name )?></span>
					</a>
				</div>


				<!--logo and logo icon end-->

				<!--toggle button start-->
				<a class="toggle-btn"><i class="fa fa-outdent"></i></a>
				<!--toggle button end-->

				<!--mega menu start-->

				<!--mega menu end-->
				<div class="notification-wrap">



					<!--right notification start-->
					<div class="right-notification">
						<ul class="notification-menu">

							<!-- <div class="navbar-text">
							  /* echo \lajax\languagepicker\widgets\LanguagePicker::widget ( [
							 		'skin' => \lajax\languagepicker\widgets\LanguagePicker::SKIN_BUTTON,
							 		'size' => \lajax\languagepicker\widgets\LanguagePicker::SIZE_LARGE

							 ]);
							 </div> -->
							<li><a href="javascript:;"
								class="btn btn-default dropdown-toggle" data-toggle="dropdown">


								                 <?php
																									
																									echo Html::img ( [ 
																											'/user/download',
																											'profile_file' => Yii::$app->user->identity->profile_file 
																									], [ 
																											'height' => '50',
																											'width' => '50' 
																									] )?>




                                <?php
																																
																																echo Yii::$app->user->identity->first_name;
																																?>
                                <span class=" fa fa-angle-down"></span>
							</a>
								<ul class="dropdown-menu dropdown-usermenu purple pull-right">
									<li><a
										href="<?php
										
										echo Url::toRoute ( [ 
												'/user/view',
												'id' => Yii::$app->user->id 
										] );
										?>"> <i class="fa fa-user pull-right"></i> Profile
									</a></li>
									<li><a
										href="<?php
										
										echo Url::toRoute ( [ 
												'/user/changepassword',
												'id' => Yii::$app->user->id 
										] );
										?>"> <span class="fa fa-key pull-right"></span> <span>Change
												Password</span>
									</a></li>
									<li><a
										href="<?php
										
										echo Url::toRoute ( [ 
												'/user/logout' 
										] );
										?>"> <i class="fa fa-sign-out pull-right"></i> Log Out
									</a></li>
								</ul></li>


						</ul>
					</div>
					<!--right notification end-->
				</div>


			</div>

			<!-- header section end-->

			<!-- page head start-->
			 <?=Breadcrumbs::widget ( [ 'links' => isset ( $this->params ['breadcrumbs'] ) ? $this->params ['breadcrumbs'] : [ ] ] )?>
			<!--body wrapper start-->
			<section class="main_wrapper">

			<?=ShadowWidget::widget ()?>
                <?=$content;?>

			</section>

		<footer>
			<div class="text-center footer-bottom">
				<?php echo ' &copy; ' . date('Y').' '. Yii::$app->name .' | All Rights Reserved | Powered by <a href="'.Yii::$app->params['companyUrl'].'">KindWorker</a>' ; ?>
	   		</div>
		</footer>
 
			<!--footer section start-->
			<!--footer section end-->
			<!--body wrapper end-->
		</div>


		<!-- body content end-->

	</section>
<audio id="sound_play" src="<?= $this->theme->getUrl('audio/notify.wav') ?>">
		</audio>
	<!-- Placed js at the end of the document so the pages load faster -->
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/jquery-migrate.js' )?>"></script>
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/bootstrap.min.js' )?>"></script>
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/modernizr.min.js' )?>"></script>

	<!--Nice Scroll-->
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/jquery.nicescroll.js' )?>"
		type="text/javascript"></script>

	<!--right slidebar-->
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/slidebars.min.js' )?>"></script>

	<!--switchery-->
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/switchery/switchery.min.js' )?>"></script>
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/switchery/switchery-init.js' )?>"></script>

	<!--Sparkline Chart-->
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/sparkline/jquery.sparkline.js' )?>"></script>
	<script
		src="<?php
		
		echo $this->theme->getUrl ( 'js/sparkline/sparkline-init.js' )?>"></script>
<script
  src="<?php echo $this->theme->getUrl('js/jquery-toast/jquery.toast.min.js')?>"
  type="text/javascript"></script>

	<!--common scripts for all pages-->
	<script src="<?php
	
	echo $this->theme->getUrl ( 'js/scripts.js' )?>"></script>
	<script src="<?php
	
	echo $this->theme->getUrl ( 'js/dropzone.js' )?>"></script>


<?php

$this->endBody ()?>


</body>
<script>
$(document).ready(function() {
	getNotify();
	
	function setHeight() {
		windowHeight = $(window).innerHeight() -40;
		$('.well').css('min-height', windowHeight);
	}
	;
	setHeight();
	$(window).resize(function() {
		setHeight();
	});
	setInterval( function() { getNotify() }, 5000); 
});
function getNotify() {
	$.ajax({
    	url : '<?= Url::toRoute(['/user/toast-notification']) ?>',
    	success: function ( response ) {
    		if( response.status == 'OK' ) {
        		$.each(response.list, function (index, value) {
        			$.toast({
        			    heading: 'A new Request has been arrived from '+ value.user.full_name,
        			    
        			    text: value.user.email+'<br><a href="<?= Url::toRoute(['/user/flag-index?id=']) ?>'+value.id+'"  class="btn btn-sucess"> Click here </a>',
        			   // hideAfter: false,
        			    position: 'top-right',
        			    icon: 'info',
        			    loader: false,        // Change it to false to disable loader
        			    loaderBg: '#9EC600'  // To change the background
        			});
				});
        		var playSound = $('#sound_play')[0];
				playSound.play();
			}
		}
	});	
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".child-list").find('span').contents().unwrap();
});
</script>
<script
	src="<?php
	
	echo $this->theme->getUrl ( 'dist/sweetalert.min.js' )?>"></script>

<link
	href="<?php
	
	echo $this->theme->getUrl ( 'dist/sweetalert.css' )?>"
	rel="stylesheet">

<script>

$(document).on('click', ".delete-data",function(e) {
	e.preventDefault();
	e.stopPropagation();

	var url = $(this).attr('data-url');
	
	swal({
	  title: "Are you sure you want to delete this record ?",
	  text: "You will not be able to recover this record !",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "Yes, delete it!",
	  cancelButtonText: "No, cancel!",
	  closeOnConfirm: false,
	  closeOnCancel: false
	},
	function(isConfirm){
	  if (isConfirm) {
		  $.ajax({
				url : url,
				type: 'POST' 
		  });
	  } else {
		    swal("Cancelled", "Record is safe", "error");
	  }
	});
}); 
$(document).on('click', ".view-data",function(e) {
	e.preventDefault();
	e.stopPropagation();

	var url = $(this).attr('data-url');

	 window.location.href = url;
});
$(document).on('click', ".update-data",function(e) {
	e.preventDefault();
	e.stopPropagation();

	var url = $(this).attr('data-url');

	 window.location.href = url;
});
</script>

<script>
$('#load').on('click', function() {
   $(".loader-bg").show();
    $(".loader").show();


    setTimeout(function () {
    	$(".loader-bg").hide();
        $(".loader").hide();
                
    }, 1000);

});

</script>
<?php

$this->endPage ()?>

</html>
