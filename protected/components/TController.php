<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\models\Log;
use app\models\Page;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\Controller;

class TController extends Controller {
	public $layout = '//main';
	public $menu = [ ];
	public $top_menu = [ ];
	public $side_menu = [ ];
	public $user_menu = [ ];
	public $tabs_data = null;
	public $tabs_name = null;
	public $dryRun = false;
	public $assetsDir = '@webroot/assets';
	public $ignoreDirs = [ ];
	public $nav_left = '';
	private $_pageCaption = 'Kind-Worker';
	private $_pageDescription = 'The Kind Worker app is an on-demand mobile platform that connects self-employed providers with users who need chores and small jobs completed.';
	private $_pageKeywords = 'Kind Worker , jobs , app';
	public function actions() {
		return [ 
				'error' => [ 
						'class' => 'yii\web\ErrorAction' 
				],
				'captcha' => [ 
						'class' => 'yii\captcha\CaptchaAction',
						'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null 
				] 
		];
	}
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'only' => [ 
								'index',
								'view',
								'create',
								'update',
								'delete' 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'index',
                                                'add',
												'view',
												'create',
												'update' ,
												'mass',
										],
										'allow' => true,
										'matchCallback' => function ($rule, $action) {
											return User::isAdmin ();
										} 
								],
								[ 
										'actions' => [ 
												'delete' 
										],
										'allow' => true,
										'matchCallback' => function ($rule, $action) {
											return User::isAdmin ();
										} 
								] 
						] 
				],
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	public function afterAction($action, $result) {
		return parent::afterAction ( $action, $result );
	}
	public static function cleanRuntimeDir($dir, $delete = false) {
		if (is_dir ( $dir )) {
			$objects = scandir ( $dir );
			
			$objects = FileHelper::findFiles ( $dir );
			foreach ( $objects as $object ) {
				if (unlink ( $object )) {
					Yii::$app->session->setFlash ( 'runtime_clean', Yii::t ( 'app', 'Runtime cleaned' ) );
				}
			}
			reset ( $objects );
			
			if ($delete) {
				FileHelper::removeDirectory ( $dir );
			}
		}
	}
	
	public function cleanAssetsDir() {
		$assetsDirs = glob ( Yii::getAlias ( $this->assetsDir ) . '/*', GLOB_ONLYDIR );
		foreach ( $assetsDirs as $dir ) {
			if (in_array ( basename ( $dir ), $this->ignoreDirs )) {
				continue;
			}
			if (! $this->dryRun) {
				FileHelper::removeDirectory ( $dir );
			}
		}
		Yii::$app->session->setFlash ( 'assets_clean', Yii::t ( 'app', 'Assets cleaned' ) );
	}
	public function processSEO($model = null) {
		if (\yii::$app->getModule ( 'seoManager' )) {
			\yii::$app->seomanager->processSEO ( $this->id, $this->action->id );
		}
		
		if ($model && ($model instanceof TActiveRecord && ! $model->isNewRecord)) {
			$this->_pageCaption = Html::encode ( $model->label () ) . ' - ' . Html::encode ( $model ) . ' | ' . $this->_pageCaption;
			
			if ($model->hasAttribute ( 'content' ))
				$this->_pageDescription = substr ( strip_tags ( $model->content ), 0, 150 );
			else if ($model->hasAttribute ( 'description' ))
				$this->_pageDescription = substr ( strip_tags ( $model->description ), 0, 150 );
		} elseif ($this->action->id == 'index' && $this->id == 'site') {
			$this->_pageCaption = $this->_pageCaption;
		} else {
			$this->_pageCaption = Inflector::pluralize ( Inflector::camel2words ( Yii::$app->controller->id ) ) . '-' . Inflector::camel2words ( $this->action->id ) . ' | ' . $this->_pageCaption;
		}
		$this->getView ()->registerMetaTag ( [ 
				'name' => 'description',
				'content' => $this->_pageDescription 
		] );
		$this->getView ()->registerMetaTag ( [ 
				'name' => 'keywords',
				'content' => $this->_pageKeywords 
		] );
		$this->getView ()->registerMetaTag ( [ 
				'name' => 'author',
				'content' => '@toxsltech' 
		] );
		
		$this->getView ()->title = $this->_pageCaption;
		
		$this->getView ()->registerLinkTag ( [ 
				'rel' => 'canonical',
				'href' => Url::canonical () 
		] );
		$this->getView ()->registerMetaTag ( [ 
				'name' => 'google-site-verification',
				'content' => 'OZngflKigK2CwEwC2PGJKDGL4oLU2gnAVKhjG-lhAfQ' 
		] );
	}
	public function beforeAction($action) {
		if (! file_exists ( DB_CONFIG_FILE_PATH )) {
			if (\Yii::$app->controller->id != 'install') {
				$this->redirect ( [ 
						"/install" 
				] );
				return false;
			}
		}
		
		if (! yii::$app->user->isGuest) {
			$this->layout = 'main';
		} else {
			$this->layout = 'guest-main';
		}
		
		return parent::beforeAction ( $action );
	}
	public function actionPdf() {
		Yii::$app->response->format = 'pdf';
		$this->layout = '//print';
		return $this->render ( 'myview', [ ] );
	}
	public function startPanel($name = 'tabpanel1') {
		$this->tabs_name = $name;
		$this->tabs_data = array ();
	}
	public function addPanel($title, $objects, $relation, $model = null, $addMenu = true) {
		$view = Inflector::camel2id ( $relation );
		if ($addMenu)
			$this->user_menu [] = array (
					'label' => Yii::t ( 'app', 'Add ' ) . $title,
					'icon' => 'plus ',
					'url' => [ 
							$view . '/add',
							'type' => $model ? get_class ( $model ) : null 
					] 
			);
		
		if ($objects) {
			if ($objects instanceof ActiveDataProvider)
				$dataProvider = $objects;
			elseif ($objects instanceof ActiveQuery)
				$dataProvider = new ActiveDataProvider ( [ 
						'query' => $objects 
				] );
			else {
				
				$type = get_class ( $model );
				
				$this->tabs_data [] = array (
						'label' => $title,
						'url' => [ 
								"$view/ajax",
								'type' => "$type",
								'function' => "$objects",
								'id' => $model->id 
						],
						'active' => count ( $this->tabs_data ) == 0 ? true : false 
				);
			}
		}
	}
	public function endPanel() {
		echo \yii\jui\Tabs::widget ( [ 
				
				'items' => $this->tabs_data,
				'options' => array (
						'class' => 'tabbable tabs-left ui-tabs-vertical ui-helper-clearfix ui-tabs-vertical' 
				) 
		] );
	}
	public function actionAjax($type, $id, $function, $grid = '_ajax-grid') {
		$model = $type::findOne ( [ 
				'id' => $id 
		] );
		
		if (! empty ( $model )) {
			if (! ($model->isAllowed ()))
				throw new \yii\web\HttpException ( 403, Yii::t ( 'app', 'You are not allowed to access this page.' ) );
			$function = 'get' . ucfirst ( $function );
			$dataProvider = new ActiveDataProvider ( [ 
					'query' => $model->$function () 
			] );
			
			echo $this->renderAjax ( $grid, [ 
					'dataProvider' => $dataProvider,
					'enablePushState' => false,
					'searchModel' => null 
			] );
		}
	}
	public function render($view, $params = []) {
		if (array_key_exists ( 'model', $params )) {
			$this->processSEO ( $params ['model'] );
		} else
			$this->processSEO ();
		return parent::render ( $view, $params );
	}
	protected function updateMenuItems($model = null) {
		switch (\Yii::$app->controller->action->id) {
			
			default :
			case 'view' :
				{
					$this->menu ['add'] = array (
							'label' => '<span class="glyphicon glyphicon-plus"></span>',
							'title' => Yii::t ( 'app', 'Add' ),
							'url' => [ 
									'add' 
							],
							'visible' => User::isAdmin () 
					);
//					$this->menu ['manage'] = array (
//							'label' => '<span class="glyphicon glyphicon-list"></span>',
//							'title' => 'Manage',
//							'url' => array (
//									'index'
//							),
//							'visible' => User::isAdmin ()
//					);
				}
				break;

            case 'add' :
				{
                    $this->menu ['manage'] = array (
							'label' => '<span class="glyphicon glyphicon-list"></span>',
							'title' => 'Manage',
							'url' => array (
									'index'
							),
							'visible' => User::isAdmin ()
					);
                }
		}
	}
	public function actionMass($action = 'delete',$model) {
		
		\Yii::$app->response->format = 'json';
		$response ['status'] = 'NOK';
		$Ids = Yii::$app->request->post ( 'ids', [ ] );
		//$model = Yii::$app->request->post ( 'model', '' );
		
		if (! empty ( $Ids )) {
			foreach ( $Ids as $Id ) {
				$model = $model::findOne ( $Id );
				
				if (! empty ( $model ) && $model instanceof TActiveRecord) {
					if ($action == 'delete') {
						if ($model->id !== \Yii::$app->user->id) {
						
							$model->delete ();
						}
					}
				}
			}
			$response ['status'] = 'OK';
		}
		
		return $response;
	}
	public static function addmenu($label, $link, $icon, $visible = null, $list = null) {
		if (! $visible)
			return null;
		$item = [ 
				'label' => '<i
							class="fa fa-' . $icon . '"></i> <span>' . $label . '</span>',
				'url' => [ 
						$link 
				] 
		];
		if ($list != null) {
			$item ['options'] = [ 
					'class' => 'menu-list' 
			];
			
			$item ['items'] = $list;
		}
		
		return $item;
	}
	public function renderNav() {
		$nav = [ 
				
				self::addMenu ( Yii::t ( 'app', 'Dashboard' ), '//dashboard/index', 'home', (User::isAdmin () || ! User::isAdmin ()) ),

				self::addMenu ( Yii::t ( 'app', 'Customers' ), '//user/customer-index', 'user', (User::isAdmin () || ! User::isAdmin ()) ),
				
				self::addMenu ( Yii::t ( 'app', 'Worker Request' ), '//', 'square-o', (User::isAdmin () || ! User::isAdmin ()), [
						self::addMenu ( Yii::t ( 'app', 'Pending Request' ), '//user/request-index/', '', (User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Approved User' ), '//user/approval-index/', '', (User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Banned User' ), '//user/banned-index/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Denied User' ), '//user/deny-index/', '', (User::isAdmin () || ! User::isAdmin ()) )
				] ),
				self::addMenu ( Yii::t ( 'app', 'Worker Skill' ), '//worker-skill/index', 'square-o', (User::isAdmin () || ! User::isAdmin ()) ),
				
				self::addMenu ( Yii::t ( 'app', 'Jobs' ), '//job', 'handshake-o', (User::isAdmin () || ! User::isAdmin ()), [ 
						
						self::addMenu ( Yii::t ( 'app', 'Jobs' ), '//job/custom-job/', 'custom-job', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Booked Workers ' ), '//job/booked-workers/', 'booked-workers', (User::isAdmin () || ! User::isAdmin ()) ) 
				] ),
				self::addMenu ( Yii::t ( 'app', 'Service' ), '//category', 'briefcase', (User::isAdmin () || ! User::isAdmin ()), [
				
						self::addMenu ( Yii::t ( 'app', 'Category' ), '//category/index/', 'category', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Sub Category' ), '//sub-category/index/', 'sub-category', (User::isAdmin () || ! User::isAdmin ()) )
				] ),
				
				self::addMenu ( Yii::t ( 'app', 'Bids' ), '//bid/index', 'bookmark', (User::isAdmin () || ! User::isAdmin ()) ),
				
				self::addMenu ( Yii::t ( 'app', 'Notifications' ), '//notification/index', 'bell', (User::isAdmin () || ! User::isAdmin ()) ),
				self::addMenu ( Yii::t ( 'app', 'Send Notification' ), '//notification/application', 'bell', (User::isAdmin () || ! User::isAdmin ()) ),
				
				self::addMenu ( Yii::t ( 'app', 'Ratings' ), '//rating/index', 'star', (User::isAdmin () || ! User::isAdmin ()) ),
				
				
		
				
				self::addMenu ( Yii::t ( 'app', 'Worker Manage' ), '#', 'map', (User::isAdmin () || ! User::isAdmin ()), [ 
						self::addMenu ( Yii::t ( 'app', 'Worker Amount' ), '//worker-amount/index/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Worker City' ), '//city/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Worker State' ), '//state/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Worker Country' ), '//country/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Worker Policy' ), '//policy/', '', (User::isAdmin ()))
				] ),
				
				self::addMenu ( Yii::t ( 'app', 'Manage' ), '#', 'tasks', (User::isAdmin () || ! User::isAdmin ()), array(
						self::addMenu ( Yii::t ( 'app', 'Logs' ), '//log/', '', (User::isAdmin () || ! User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Login History' ), '//login-history/', '', (User::isAdmin () || ! User::isAdmin ()) ),
                        self::addMenu ( Yii::t ( 'app', 'Page' ), '//page/', '', (User::isAdmin ()) ),
                        //self::addMenu ( Yii::t ( 'app', 'Backup' ), '//backup/', '', (User::isAdmin ()) ) ,
						self::addMenu ( Yii::t ( 'app', 'Commission' ), '//commission/', '', (User::isAdmin ()) ),
						self::addMenu ( Yii::t ( 'app', 'Budget' ), '//budget/', '', (User::isAdmin ()) )

                )),

		];
	 
		return $nav;
	}
}
