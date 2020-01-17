<?php

namespace app\components;

use yii;

class AppTheme extends \yii\base\Theme {
	public $name = 'base';
	public $style = '';
	public function init() {
		parent::init ();
		
		//$this->name = Yii::$app->Setting->get ( 'theme', 'base' );
		
		if (isset ( $_GET ['theme'] ) && ! empty ( $_GET ['theme'] )) {
			$this->name = $_GET ['theme'];
		}
		
		if (! isset ( $this->name )) {
			$this->name = 'base';
		}
		if (strpos ( $this->name, ':' )) {
			
			$data = explode ( ':', $this->name );
			$this->name = $data [0];
			$this->style = $data [1];
		}
		
		$this->basePath = '@app/../themes/' . $this->name;
		$this->baseUrl = '@web/themes/' . $this->name;
		$this->pathMap = [ 
				'@app/views' => '@app/../themes/' . $this->name . '/views' 
		];
	}
}

