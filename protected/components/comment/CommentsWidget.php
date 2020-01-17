<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\comment;

use app\models\Comment;
use yii\data\ActiveDataProvider;

/**
 * This is just an example.
 */
class CommentsWidget extends \yii\base\Widget {
	public $disabled = true;
	
	/**
	 *
	 * @var Model
	 */
	public $model;
	public $readOnly = false;
	protected function getRecentComments() {
		if ($this->model == null)
			return null;
		$query = Comment::find ( [ 
				'model_type' => get_class ( $this->model ),
				'model_id' => $this->model->id 
		] );
		return new ActiveDataProvider ( [ 
				'query' => $query 
		] );
	}
	protected function formModel() {
		$comment = null;
		if ($this->readOnly == false) {
			$comment = new Comment ();
			$comment->model_type = get_class ( $this->model );
			$comment->model_id = $this->model->id;
		}
		return $comment;
	}
	public function run() {
		if ($this->disabled)
			return; // Do nothing
		
		if (isset ( $_POST ['Comment'] )) {
			$comment = new Comment ();
			$comment->load ( $_POST );
			$comment->model_type = get_class ( $this->model );
			$comment->model_id = $this->model->id;
			
			$comment->save ();
		}
		
		echo $this->render ( 'comments', [ 
				'comments' => $this->getRecentComments (),
				'model' => $this->formModel () 
		] );
	}
}
