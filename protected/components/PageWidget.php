<?php

namespace app\components;

use app\models\Page;
use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class PageWidget extends \yii\base\Widget {
	public $id;
	public $header;
	public $title;
	public $icon = "fa fa-tasks";
	public $color;
	public $title_tag = 'h4';
	public $title_class = 'box-title';
	public $para_class = '';
	public $span_class = '';
	// public $style="";
	public $colors = array (
			'blue',
			'red',
			'yellow',
			'green' 
	);
	public function init() {
		parent::init ();
		ob_start ();
	}
	public function run() {
		$content = ob_get_clean ();
		$page = null;
		if ($this->id != null)
			$page = Page::findOne ( $this->id );
		else if ($this->header != null)
			$page = Page::find ()->Where ( [					
					'header'=>
					$this->header 
			] )->one ();
		
		if ($page != null) {
			$this->renderPage ( $page );
		} else {
			echo Html::tag ( $this->title_tag, \yii\helpers\Html::encode($this->header), array (
					'class' => $this->title_class 
			) );
			if (! \Yii::$app->user->isGuest) {
				echo Html::tag ( 'a', '<i class="fa fa-plus has-circle"></i>', array (
						
						'href' => Url::to ( [ 
								'page/add',
								'title' => $this->header 
						] ),
						'target'=>'_blank'
				) );
			}
		}
	}
	public function renderPage($page) {
		switch ($page->type_id) {
			case Page::TYPE_ARTICLE :
				$this->renderPageArticle ( $page );
				break;
			case Page::TYPE_PARA :
				$this->renderPagePara ( $page );
				break;
			case Page::TYPE_LINE :
				$this->renderPageContent ( $page );
				break;
		}
	}
	public function renderPageArticle($page) {
		echo Html::tag ( 'h1', \yii\helpers\Html::encode($page->title) );
		$this->renderPageContent ( $page );
	}
	public function renderPagePara($page) {
		if ($page->url != null) {
			echo Html::tag ( $this->title_tag,				
						$page->getTempUrl(), array (
					'class' => $this->title_class 
			) );
		} else {
			echo Html::tag ( $this->title_tag, \yii\helpers\Html::encode($page->title), array (
					'class' => $this->title_class 
			) );
		}
		$this->renderPageContent ( $page );
	}
	public function renderPageContent($page) {
		if (! Yii::$app->user->isGuest) {
			
			echo Html::tag ( 'a', '<i class="fa fa-pencil has-circle"></i>', array (					
					'href' => Url::to ( [ 
							'page/update',
							'id' => $page->id 
					] ),
					'target'=>'_blank'
			) );
		}
		echo Html::tag ( 'p', \yii\helpers\HtmlPurifier::process($page->description), array (
				'class' => $this->para_class 
		) );
	}
}
