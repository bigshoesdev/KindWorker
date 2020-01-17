<?php

namespace app\components;

use Yii;
use yii\helpers\Html;

class TActionColumn extends \yii\grid\ActionColumn {
	function init() {
		parent::init ();
		$this->urlCreator = function ($action, $model, $key, $index) {
			
			return $model->getUrl ( $action );
		};
	}
	protected function initDefaultButtons() {
		$this->initDefaultButton ( 'view', 'eye-open', [
				'class' => 'view-data',
		] );
		$this->initDefaultButton ( 'update', 'pencil', [
				'class' => 'update-data'
		] );
		$this->initDefaultButton ( 'delete', 'trash', [
				// 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
				'data-method' => 'post',
				'class' => 'delete-data' 
		] );
	}
	protected function initDefaultButton($name, $iconName, $additionalOptions = []) {
		if (! isset ( $this->buttons [$name] ) && strpos ( $this->template, '{' . $name . '}' ) !== false) {
			$this->buttons [$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
				switch ($name) {
					case 'view' :
						$title = Yii::t ( 'yii', 'View' );
						break;
					case 'update' :
						$title = Yii::t ( 'yii', 'Update' );
						break;
					case 'delete' :
						$title = Yii::t ( 'yii', 'Delete' );
						break;
					default :
						$title = ucfirst ( $name );
				}
				$options = array_merge ( [ 
						'title' => $title,
						'aria-label' => $title,
						'data-url' => $url,
						'data-pjax' => '0' 
				], $additionalOptions, $this->buttonOptions );
				$icon = Html::tag ( 'span', '', [ 
						'class' => "glyphicon glyphicon-$iconName" 
				] );
				return Html::a ( $icon, 'javascript:;', $options );
			};
		}
	}
}