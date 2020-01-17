<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LoginHistory as LoginHistoryModel;

/**
 * LoginHistory represents the model behind the search form about `app\models\LoginHistory`.
 */
class LoginHistory extends LoginHistoryModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'state_id',
								'type_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'user_ip',
								'user_agent',
								'failer_reason',
								'code',
								'created_on',
								'name' 
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
		$query = LoginHistoryModel::find ()->alias ( 'l' );
		$query->joinwith ( 'user as u' );
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		
		$dataProvider->sort->attributes ['name'] = [ 
				// The tables are the ones our relation are configured to
				// in my case they are prefixed with "tbl_"
				'asc' => [ 
						'u.first_name' => SORT_ASC 
				],
				'desc' => [ 
						'u.first_name' => SORT_DESC 
				] 
		];
		
		if (! ($this->load ( $params ) && $this->validate ())) {
			return $dataProvider;
		}
		
		$query->andFilterWhere ( [ 
				'l.id' => $this->id,
				'l.state_id' => $this->state_id,
				'l.type_id' => $this->type_id,
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'l.user_ip',
				$this->user_ip 
		] )->andFilterWhere ( [ 
				'like',
				'l.user_agent',
				$this->user_agent 
		] )->andFilterWhere ( [ 
				'like',
				'l.failer_reason',
				$this->failer_reason 
		] )->andFilterWhere ( [ 
				'like',
				'l.code',
				$this->code 
		] )->andFilterWhere ( [ 
				'like',
				'u.first_name',
				$this->name 
		
		] )->andFilterWhere([
			    'like',
				'l.created_on',
				$this->created_on
		]);
		
		return $dataProvider;
	}
}
