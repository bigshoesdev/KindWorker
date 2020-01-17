<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Service as ServiceModel;

/**
 * Service represents the model behind the search form about `app\models\Service`.
 */
class Service extends ServiceModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'service_type',
								'category_id',
								'sub_category_id',
								'rate_type',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'description',
								'price',
								'zipcode',
								'created_on',
								'updated_on' 
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
		$query = ServiceModel::find ();
		
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
				'service_type' => $this->service_type,
				'category_id' => $this->category_id,
				'sub_category_id' => $this->sub_category_id,
				'rate_type' => $this->rate_type,
				'state_id' => $this->state_id,
				'type_id' => $this->type_id,
				'created_on' => $this->created_on,
				'updated_on' => $this->updated_on,
				'created_by_id' => $this->created_by_id 
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'description',
				$this->description 
		] )->andFilterWhere ( [ 
				'like',
				'price',
				$this->price 
		] )->andFilterWhere ( [ 
				'like',
				'zipcode',
				$this->zipcode 
		] );
		
		return $dataProvider;
	}
	public function searchService($params) {
		
		$query = ServiceModel::find ()->alias ( 's' )->joinWith ( 'category as c' );
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				] 
		] );
		
		
    	$query->andFilterWhere ( [
    			'like',
    			'c.title',
    			$params['Category']['search']
    	] )->andFilterWhere ( [
    			'like',
    			's.service_type',
    			$params['Category']['type']
    	] );
		
		return $dataProvider;
	}
}
