<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction as TransactionModel;

/**
 * Transaction represents the model behind the search form about `app\models\Transaction`.
 */
class Transaction extends TransactionModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'payment_mode',
								'payer_id',
								'reciever_id',
								'model_id',
								'state_id',
								'type_id',
								'role_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'transaction_num',
								'currency',
								'amount',
								'model_type',
								'created_on' 
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
		$query = TransactionModel::find ();
		
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
				'id' => $this->id,
				'payment_mode' => $this->payment_mode,
				'payer_id' => $this->payer_id,
				'reciever_id' => $this->reciever_id,
				'model_id' => $this->model_id,
				'state_id' => $this->state_id,
				'type_id' => $this->type_id,
				'role_id' => $this->role_id,
				'created_on' => $this->created_on,
				'created_by_id' => $this->created_by_id 
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'transaction_num',
				$this->transaction_num 
		] )->andFilterWhere ( [ 
				'like',
				'currency',
				$this->currency 
		] )->andFilterWhere ( [ 
				'like',
				'amount',
				$this->amount 
		] )->andFilterWhere ( [ 
				'like',
				'model_type',
				$this->model_type 
		] );
		
		return $dataProvider;
	}
}
