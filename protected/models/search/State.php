<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\State as StateModel;

/**
 * State represents the model behind the search form about `app\models\State`.
 */
class State extends StateModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'type_id',
								'state_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'name',
								'created_by_id',
								'country_id' 
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
		$query = StateModel::find ()->alias ( 's' )->joinWith ( 'createdBy as cb' )->joinWith ( 'country as c' );
		
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
				's.id' => $this->id,
				// 's.country_id' => $this->country_id,
				's.type_id' => $this->type_id,
				's.state_id' => $this->state_id 
			// 's.createdBy' => $this->created_by_id,
		] );
		$query->andFilterWhere ( [ 
				'like',
				's.name',
				$this->name 
		] );
		$query->FilterWhere ( [ 
				'like',
				'cb.first_name',
				$this->created_by_id 
		] );
		$query->FilterWhere(['like', 'c.name', $this->country_id]); 
        return $dataProvider;
    }
}
