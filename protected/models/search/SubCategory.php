<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubCategory as SubCategoryModel;

/**
 * SubCategory represents the model behind the search form about `app\models\SubCategory`.
 */
class SubCategory extends SubCategoryModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'state_id',
							  
						],
						'integer' 
				],
				[ 
						[ 
								'title',
								'created_on',
								'category_id' 
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
		$query = SubCategoryModel::find ()->alias('s')->joinWith('category as c');
		
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
				's.state_id' => $this->state_id 
		
		] );
		$query->andFilterWhere ( [ 
				'like',
				's.created_on',
				$this->created_on 
		] )->andFilterWhere ( [ 
				'like',
				'c.title',
				$this->category_id 
		] )
		->andFilterWhere ( [
				'like',
				's.title',
				$this->title
		] );
		
		return $dataProvider;
	}
    public function searchCategoryID($id) {

        $query = SubCategory::find ()->alias ( 'sc' )->andWhere ( [
            'sc.id' => $id,
        ] );
        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ] );
        return $dataProvider;
    }

}
 
