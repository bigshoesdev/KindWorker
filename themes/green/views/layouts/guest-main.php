<?php
use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title = yii::$app->name;
AppAsset::register ( $this );
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1">
<meta charset="<?= Yii::$app->charset ?>" />
    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
    

<link rel="shortcut icon"
	href="<?= $this->theme->getUrl('img/favicon.ico')?>" type="image/ico">
	
<link rel="canonical" href="<?php echo Url::canonical();?>">
<!--common style-->
<link href="<?php echo $this->theme->getUrl('css/style.css')?>"
	rel="stylesheet">
	
<link
	href="<?php echo $this->theme->getUrl('css/style-responsive.css')?>"
	rel="stylesheet">
<!--theme color layout-->
<link
	href="<?php echo $this->theme->getUrl('css/layout-theme-two.css')?>"
	rel="stylesheet">
<script type="text/javascript"
	src="<?php echo $this->theme->getUrl('js/custom.js')?>"></script>

</head>
<body class="sticky-header">
<?php $this->beginBody()?>

	<section>
		<header role="banner" id="top"
			class="navbar navbar-static-top bs-docs-nav bg-danger light-color ">
			<div class="container">
				<div class="navbar-header">
					<button aria-expanded="false" aria-controls="bs-navbar"
						data-target="#bs-navbar" data-toggle="collapse" type="button"
						class="navbar-toggle collapsed">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>

					<a class="navbar-brand" href="<?php echo Url::home();?>">
						&nbsp; <span
						class="brand-name"><?= Yii::$app->name?> </span>
					</a>

				</div>
				<nav class="collapse navbar-collapse" id="bs-navbar">
					<ul class=" nav navbar-nav navbar-right mega-menu">
						<!-- <li><a href="<?php //echo Url::to(['site/contact']);?>">Contact</a>
						</li>
						<li>-->
						
						<?php   if(User::isGuest()){?>
						<li><a href="<?//php echo Url::to(['user/signup']);?>"></a></li>
						<li><a href="<?php echo Url::to(['user/login']);?>">Login</a></li>
						
						<?php
						} else {
							?>
							<li><a href="<?php echo Url::to(['user/dashboard']);?>">Dashboard</a></li>
							
							
					<?php 	}?>
					</ul>


				</nav>
			</div>
		</header>

		<!-- body content start-->

		<div class="main_wrapper well site-index bg relative no-pad">
			<div class="img-overlay"></div>
			<div class="header no-pad">
				<!-- header section start-->

				<!--body wrapper start-->

			
		
                 <?= $content?>

          </div>
		</div>
		<!--body wrapper end-->
		 		<footer>
			<div class="text-center footer-bottom">
				<?php echo ' &copy; ' . date('Y').' '. Yii::$app->name .' | All Rights Reserved | Powered by <a href="'.Yii::$app->params['companyUrl'].'">Kind Worker </a>' ; ?>
	   		</div>
		</footer>

		<!-- body content end-->

	</section>

	<!-- Placed js at the end of the document so the pages load faster -->



	<script src="<?php echo $this->theme->getUrl('js/bootstrap.min.js')?>"></script>


	<!--Nice Scroll-->
	<script
		src="<?php echo $this->theme->getUrl('js/jquery.nicescroll.js')?>"
		type="text/javascript"></script>

	<!--right slidebar-->
	<script src="<?php echo $this->theme->getUrl('js/slidebars.min.js')?>"></script>




	<!--common scripts for all pages-->
	<script src="<?php echo $this->theme->getUrl('js/scripts.js')?>"></script>
<?php $this->endBody()?>



	<script>
	$(document).ready(function() {
		  function setHeight() {
		    windowHeight = $(window).innerHeight() -100;
		    $('.main_wrapper').css('min-height', windowHeight);
		  };
		  setHeight();	  
		  $(window).resize(function() {
		    setHeight();
		  });
	


			
	});
</script>
</body>


<?php $this->endPage()?>

</html>
