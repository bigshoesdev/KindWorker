<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rating as RatingModel;

/**
 * Rating represents the model behind the search form about `app\models\Rating`.
 */
class Rating extends RatingModel {
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
								'rate',
								'comment',
								'model_type',
								'created_on',
								'model_id',
								'state_id',
								'type_id',
								'created_by_id',
								'user_id' 
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
		$query = RatingModel::find ()->alias ( 'r' )->joinWith ( 'job as j' )->joinWith ( 'user as u' )->joinWith ( 'createdBy as cb' );
		
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
				'r.id' => $this->id,
				'r.state_id' => $this->state_id,
				'r.type_id' => $this->type_id,
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'r.rate',
				$this->rate 
		] )->andFilterWhere ( [ 
				'like',
				'r.comment',
				$this->comment 
		] )->andFilterWhere ( [ 
				'like',
				'cb.first_name',
				$this->created_by_id 
		] )->andFilterWhere ( [ 
				'like',
				'u.first_name',
				$this->user_id 
		] )->andFilterWhere([
				'like',
				'j.title',
				$this->model_id
		]);
		
		return $dataProvider;
	}
}
