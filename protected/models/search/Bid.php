<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bid as BidModel;

/**
 * Bid represents the model behind the search form about `app\models\Bid`.
 */
class Bid extends BidModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
                    [
                        'id',
                        'deliver_in',
                        'job_id',
                    ],
                    'integer'
				],
                [
                    [
                        'bid_price',
                        'paid',
                        'created_on',
                        'updated_on',
                        'job_id',
                        'deliver_in',
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
		$query = BidModel::find ()->alias ( 'b' )->joinWith ( 'job as j' )->joinWith ( 'createdBy as cb' );
		
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
				'b.id' => $this->id,
				'b.deliver_in' => $this->deliver_in,
				'b.state_id' => $this->state_id,
				'b.type_id' => $this->type_id,
				'b.created_on' => $this->created_on,
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'bid_price',
				$this->bid_price 
		] )->andFilterWhere ( [ 
				'like',
				'paid',
				$this->paid 
		] );
		$query->FilterWhere ( [ 
				'like',
				'j.title',
				$this->job_id 
		] );
		$query->FilterWhere ( [
				'like',
				'cb.first_name',
				$this->created_by_id
		] );
		
		return $dataProvider;
	}
}
