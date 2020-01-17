<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\controllers\DashboardController;
use app\models\Bid;
use app\models\Booking;
use app\models\Category;
use app\models\Customer;
use app\models\Job;
use app\models\search\User as UserSearch;
use app\models\SubCategory;
use app\models\User;
use miloschuman\highcharts\Highcharts;
use yii\data\Pagination;
use yii\helpers\Url;

/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'Dashboard'),'url' => ['index']
];
?>

<link
	href="<?php
	
	echo $this->theme->getUrl ( 'dist/sweetalert.css' )?>"
	rel="stylesheet">

<script
	src="<?php
	
	echo $this->theme->getUrl ( 'dist/sweetalert.min.js' )?>"></script>



<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>


<div class="wrapper">
	<!--state overview start-->
	<div class="row state-overview">
		<a href="<?php echo Url::toRoute(['user/customer-index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol ">
						<i class="fa fa-users"></i>
					</div>
					<div class="value ">
						<h1 data-speed="1000" data-to="432" data-from="0" class="timer">
							<?php echo User::find ()->where ( [ 
								'role_id' => User::ROLE_CUSTOMER 
						] )->count ();
						?></h1>
						<p>Total Customers</p>
					</div>
				</section>
			</div>
		</a> <a href="<?php echo Url::toRoute(['user/worker-index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol purple-color">
						<i class="fa fa-user"></i>
					</div>
					<div class="value">
						<h1 data-speed="1000" data-to="123" data-from="0" class=" timer"><?php  echo User::find ()->where(['role_id'=>User::ROLE_WORKER])->count ();?></h1>
						<p>Total Workers</p>


					</div>
				</section>
			</div>
		</a><a href="<?php echo Url::toRoute(['category/index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol purple-color">
						<i class="fa fa-dot-circle-o"></i>
					</div>
					<div class="value">
						<h1 data-speed="1000" data-to="123" data-from="0" class="timer"><?php  echo Category::find ()->count ();?></h1>
						<p>Total Categories</p>


					</div>
				</section>
			</div>
		</a> <a href="<?php echo Url::toRoute(['bid/index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol purple-color">
						<i class="fa fa-tasks"></i>
					</div>
					<div class="value">
						<h1 data-speed="1000" data-to="123" data-from="0" class="timer"><?php  echo Bid::find ()->count ();?></h1>
						<p>Total Bids</p>


					</div>
				</section>
			</div>
		</a><a href="<?php echo Url::toRoute(['job/index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol purple-color">
						<i class="fa fa-handshake-o"></i>
					</div>
					<div class="value">
						<h1 data-speed="1000" data-to="123" data-from="0" class="timer"><?php  echo Job::find ()->count ();?></h1>
						<p>Total Jobs</p>


					</div>
				</section>
			</div>
		</a>
		
		<a href="<?php echo Url::toRoute(['sub-category/index']);?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol purple-color">
						<i class="fa fa-life-ring"></i>
					</div>
					<div class="value">
						<h1 data-speed="1000" data-to="123" data-from="0" class="timer"><?php  echo SubCategory::find ()->count ();?></h1>
						<p>Total SubCategory</p>


					</div>
				</section>
			</div>
		</a>
		

	</div>



	<div class="panel">
		<div class="panel-body">
			Welcome 
			<strong>
         	<?php
				echo Yii::$app->user->identity->full_name;
			?>
			</strong>


		</div>
	</div>

	<div class="panel">

		<div class="panel-body">

			<div class="panel-heading">
				<span class="tools pull-right">

                </span>
			</div>

            <div>
                <ul class="pagination pagination-sm">
                    <li <?php if(isset($_GET['type']) && $_GET['type']=='Daily') echo "class='active'";?>><a href="index?type=Daily">Daily</a></li>
                    <li <?php if(isset($_GET['type']) && $_GET['type']=='Weekly') echo "class='active'";?>><a href="index?type=Weekly">Weekly</a></li>
                    <li <?php if(!isset($_GET['type']) || $_GET['type']=='Monthly') echo "class='active'";?>><a href="index?type=Monthly">Monthly</a></li>

                </ul>
            </div>

			<div id="chart" class="col-md-6">
						<?php
                        ///////////////////////////Title format//////////////////////////////////////////////////////////////////

                        $check_title=Yii::$app->request->getQueryParam('type');
                        if(!isset($_GET['type'])) {
                            $check_title="Monthly";
                        }

                        if($check_title == "Monthly"){
                            $data = DashboardController::MonthlySignups ();
                            $datajob = DashboardController::MonthlyJobSignups();
                            $databid = DashboardController::MonthlyBidSignups();
                            $dataArray = array();
                            for ($i = 0; $i < 2; $i++) {
                                $dataArray[$i] = DashboardController::MonthlyCustomerSignups($i+1);
                            }
                        }elseif($check_title == "Weekly"){
                            $data = DashboardController::WeeklySignups();
                            $datajob = DashboardController::WeeklyJobSignups();
                            $databid = DashboardController::WeeklyBidSignups();
                            $dataArray = array();
                            for ($i = 0; $i < 2; $i++) {
                                $dataArray[$i] = DashboardController::WeeklyCustomerSignups($i+1);
                            }

                        }elseif($check_title == "Daily"){
                            $data = DashboardController::DailySignups();
                            $datajob = DashboardController::DailyJobSignups();
                            $databid = DashboardController::DailyBidSignups();
                            $dataArray = array();
                            for ($i = 0; $i < 2; $i++) {
                                $dataArray[$i] = DashboardController::DailyCustomerSignups($i+1);
                            }

                        }

                        echo Highcharts::widget ( [
								'options' => [ 
										'credits' => array (
												'enabled' => false
										),
										
										'title' => [ 
												'text' => $check_title . ' Registered Chart'
										],
										'chart' => [ 
												'type' => 'column'
										],
										'xAxis' => [ 
												'categories' => array_keys ( $data ) 
										],
										'yAxis' => [ 
												'title' => [ 
                                                    'text' => 'Count'
												] 
										],
										'series' => [ 
												[ 
                                                    'name' => 'Users',
                                                    'data' => array_values ( $data )
												] ,
                                                [
                                                    'name' => 'Customers',
                                                    'data' => array_values ( $dataArray[0] )
                                                ],
                                                [
                                                    'name' => 'Workers',
                                                    'data' => array_values ( $dataArray[1] )
                                                ],
                                                [
                                                    'name' => 'Jobs',
                                                    'data' => array_values ( $datajob )
                                                ],
                                                [
                                                    'name' => 'Bids',
                                                    'data' => array_values ( $databid )
                                                ]
                                        ]
								]
						
						] );
						?>
	</div>

			<div class="col-md-6">
	<?php
	$data = DashboardController::MonthlySignups ();

	?>
					<?php
					echo Highcharts::widget ( [ 
							'scripts' => [ 
									'highcharts-3d',
									'modules/exporting' 
							],
							
							'options' => [ 
									'credits' => array (
											'enabled' => false 
									),
									'chart' => [ 
											'plotBackgroundColor' => null,
											'plotBorderWidth' => null,
											'plotShadow' => false,
											'type' => 'pie' 
									],
									'title' => [ 
											'text' => 'Statistics' 
									],
									'tooltip' => [ 
											'valueSuffix' => '' 
									],
									'plotOptions' => [ 
											'pie' => [ 
													'allowPointSelect' => true,
													'cursor' => 'pointer',
													'dataLabels' => [ 
															'enabled' => true 
													],
													// 'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'
													'showInLegend' => true 
											] 
									],
									
									'htmlOptions' => [ 
											'style' => 'min-width: 100%; height: 400px; margin: 0 auto' 
									],
									'series' => [ 
											[ 
													'name' => 'Total Count',
													'colorByPoint' => true,
													
													'data' => [ 
															
														
															
															[ 
																	'name' => 'Inactive User',
																	'color' => 'yellow',
																	'y' => ( int ) User::findActive ( 0 )->count (),
																	'sliced' => true,
																	'selected' => true 
															],
															
															[ 
																	'name' => 'Active User',
																	'color' => 'green',
																	'y' => ( int ) User::findActive ()->count (),
																	'sliced' => true,
																	'selected' => true 
															] 
													] 
											] 
									] 
							] 
					] );
					?>
							</div>
		</div>

	</div>


<!--    <div class='panel' --><?php //if(User::isAdmin()) echo ""; else echo "hidden";?><!-->
<!--        <div class='panel-body'>-->
<!--            <strong>Add Sub Admin</strong>-->
<!--            <div class='text-right'>-->
<!--                    --><?//=  \app\components\TToolButtons::widget(); ?>
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </div>-->
	<div class="clearfix"></div>

    <div class="panel">
        <div class="panel-body">

            <?php
				$searchModel = new UserSearch ();
				$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
				$dataProvider->pagination->pageSize = 5;
				?>
				<?php
				
				echo $this->render ( '//user/_grid', [
						'dataProvider' => $dataProvider,
						'searchModel' => $searchModel 
				] );
				
				?>



	</div>

	</div>

</div>
