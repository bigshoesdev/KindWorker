<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;

?>
<br><br><br>
<div class="wrapper">
<div class="col-sm-offset-2 col-sm-8">
<div class="panel card-view mb-0">
									<div class="panel-wrapper collapse in">
									   <div class="panel-body">
											<div class="text-center">
						<a href="index.php"> <img src="<?php echo $this->theme->getUrl("img/error.jpeg")?>"></a>
					</div>
					<hr>
										<div class="row">
												<div class="col-sm-12 col-xs-12 text-center">
													<h3 class="mb-20 text-danger"><?php echo $name?></h3>
													<a class="btn btn-success btn-rounded green-btn btn-icon right-icon  mt-30" href="<?= Yii::$app->homeUrl ?>"><span>Back to Home</span> <i class="fa fa-space-shuttle"></i></a>
													
												</div>	
											</div>
										</div>
									</div>
								</div>
</div></div>