<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Category as CategoryModel;

/**
 * Category represents the model behind the search form about `app\models\Category`.
 */
class Category extends CategoryModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'state_id',
								'type_id',
							 
						],
						'integer' 
				],
				[ 
						[ 
								'title',
								'description',
								'image_file',
								'created_on',
								'updated_on',
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
		$query = CategoryModel::find ();
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC,
						]
				] 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ())) {
			return $dataProvider;
		}
		
		$query->andFilterWhere ( [ 
				'id' => $this->id,
				'state_id' => $this->state_id,
				'type_id' => $this->type_id,
				'updated_on' => $this->updated_on,
				'created_by_id' => $this->created_by_id 
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'title',
				$this->title 
		] )->andFilterWhere ( [ 
				'like',
				'description',
				$this->description 
		] )->
		andFilterWhere ( [ 
				'like',
				'image_file',
				$this->image_file 
		] )->andFilterWhere ( [ 
				'like', 'created_on', $this->created_on]);
            

        return $dataProvider;
    }
}
 
