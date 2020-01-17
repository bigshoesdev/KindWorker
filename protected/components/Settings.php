<?php

use yii\base\Component;
use yii\caching\Cache;

class Settings extends Component {
	protected $model;
	public $cache = 'cache';
	public $frontCache;
	private $_data = null;
	public $modelClass = 'app\model\Setting';
	public function init() {
		parent::init ();
		$this->model = new $this->modelClass ();
		if (is_string ( $this->cache )) {
			$this->cache = Yii::$app->get ( $this->cache, false );
		}
		if (is_string ( $this->frontCache )) {
			$this->frontCache = Yii::$app->get ( $this->frontCache, false );
		}
	}
	public function get($key, $value = null, $default = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key, 2 );
			if (count ( $pieces ) > 1) {
				$value = $pieces [0];
				$key = $pieces [1];
			} else {
				$value = '';
			}
		}
		
		$data = $this->getRawConfig ();
		if (isset ( $data [$section] [$key] [0] )) {
			if (in_array ( $data [$section] [$key] [1], [
					'object',
					'boolean',
					'bool',
					'integer',
					'int',
					'float',
					'string',
					'array'
			] )) {
				settype ( $data [$section] [$key] [0], $data [$section] [$key] [1] );
			}
		} else {
			$data [$section] [$key] [0] = $default;
		}
		return $data [$section] [$key] [0];
	}
	public function has($key, $value = null, $searchDisabled = false) {
		if ($searchDisabled) {
			$setting = $this->model->findSetting ( $key, $value );
		} else {
			$setting = $this->get ( $key, $value );
		}
		return is_null ( $setting ) ? false : true;
	}
	public function set($key, $value, $type = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key );
			$vlaue = $pieces [0];
			$key = $pieces [1];
		}
		if ($this->model->setSetting ( $key, $value, $type )) {
			return true;
		}
		return false;
	}
	public function getOrSet($key, $value, $type = null) {
		if ($this->has ( $key, $section, true )) {
			return $this->get ( $key );
		} else {
			return $this->set ( $key, $value, $type );
		}
	}
	public function delete($key, $value = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key );
			$value = $pieces [0];
			$key = $pieces [1];
		}
		return $this->model->deleteSetting ( $value, $key );
	}
	public function deleteAll() {
		return $this->model->deleteAllSettings ();
	}
	public function activate($key, $value = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key );
			$value = $pieces [0];
			$key = $pieces [1];
		}
		return $this->model->activateSetting ( $value, $key );
	}
	public function deactivate($key, $value = null) {
		if (is_null ( $value )) {
			$pieces = explode ( '.', $key );
			$value = $pieces [0];
			$key = $pieces [1];
		}
		return $this->model->deactivateSetting ( $value, $key );
	}
	public function clearCache() {
		$this->_data = null;
		if ($this->frontCache instanceof Cache) {
			$this->frontCache->delete ( $this->cacheKey );
		}
		if ($this->cache instanceof Cache) {
			return $this->cache->delete ( $this->cacheKey );
		}
		return true;
	}
	public function getRawConfig() {
		if ($this->_data === null) {
			if ($this->cache instanceof Cache) {
				$data = $this->cache->get ( $this->cacheKey );
				if ($data === false) {
					$data = $this->model->getSettings ();
					$this->cache->set ( $this->cacheKey, $data );
				}
			} else {
				$data = $this->model->getSettings ();
			}
			$this->_data = $data;
		}
		return $this->_data;
	}
}
