<?php

namespace app\components\useraction;

use app\components\TBaseWidget;
use app\models\User;

class UserAction extends TBaseWidget {
	public $model;
	public $attribute;
	public $states;
	public $actions;
	public $allowed;
	public $visible = true;
	public $title;
	public function init() {
		if (empty ( $this->actions ))
			$this->actions = $this->states;
		if (empty ( $this->allowed ))
			$this->allowed = $this->actions;
		// array_shift ($this->options);
		$this->title = 'Allowed Operations';
		parent::init ();
	}
	public function renderHtml() {
		if ($this->visible == false)
			return;
		
		if (isset ( $_POST ['workflow'] )) {
			$submit = trim ( $_POST ['workflow'] );
			$state_list = $this->states;
			$actions = $this->actions;
			$allowed = $this->allowed;
			
			$state_id = array_search ( $submit, $actions );
			
			$ok = array_search ( $submit, $allowed );
			if ($ok >= 0 && $state_id >= 0 && $state_id != $this->model->{$this->attribute}) {
				$old_state = $state_list [$this->model->{$this->attribute}];
				$new_state = $state_list [$state_id];
				$this->model->{$this->attribute} = $state_id;
				if ((isset ( $this->model ) && ! empty ( $this->model ))) {
					if (($this->model instanceof User) && $this->model->role_id == User::ROLE_ADMIN) {
						\Yii::$app->session->setFlash ( 'UserUpdate', 'You cannot change the login user actions' );
						
						$error = 'You are not allowed to perform this operation.';
						if ($this->model->hasErrors ()) {
							foreach ( $this->model->getErrors () as $errors )
								$error .= implode ( '.', $errors );
						}
						
						\Yii::$app->session->setFlash ( 'user-action', $error );
					} else {
						if ($this->model->save ()) {
							\Yii::$app->session->setFlash ( 'user-action', 'State Changed.' );
							$msg = 'State Changed : ' . $old_state . ' to ' . $new_state;
							// $this->model->addCommentHistory($msg);
						}
					}
				} else {
					\Yii::$app->session->setFlash ( 'UserUpdate', 'You cannot change the login user actions' );
					$error = 'You are not allowed to perform this operation.';
					if ($this->model->hasErrors ()) {
						foreach ( $this->model->getErrors () as $errors )
							$error .= implode ( '.', $errors );
					}
					
					\Yii::$app->session->setFlash ( 'user-action', $error );
				}
			}
			\Yii::$app->controller->redirect ( array (
					'view',
					'id' => $this->model->id 
			) );
		}
		
		if (! empty ( $this->model ))
			echo $this->render ( 'user-action', [ 
					'model' => $this->model,
					'allowed' => $this->allowed,
					'attribute' => $this->attribute 
			] );
	}
}