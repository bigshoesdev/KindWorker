<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Notification as NotificationModel;

/**
 * Notification represents the model behind the search form about `app\models\Notification`.
 */
class Notification extends NotificationModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id' 
						]
						,
						'integer' 
				],
				[ 
						[ 
								'message',
								'model_type',
								'create_time',
								'update_time',
								'model_id',
								'is_read',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'safe' 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios ();
	}
	public function beforeValidate() {
		return true;
	}
	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params        	
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = NotificationModel::find ()->alias ( 'n' )->joinWith ( 'createdBy as cb' )->joinWith ( 'modelUser as mu' );
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ())) {
			return $dataProvider;
		}
		
		$query->andFilterWhere ( [ 
				'n.id' => $this->id,
				'n.state_id' => $this->state_id,
				'n.type_id' => $this->type_id,
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'n.message',
				$this->message 
		] )->andFilterWhere ( [ 
				'like',
				'mu.first_name',
				$this->model_id 
		] )->andFilterWhere ( [ 
				'like',
				'cb.first_name',
				$this->created_by_id 
		] );
		
		return $dataProvider;
	}
}
