<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use app\models\UserProfile as UserProfileModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserProfile represents the model behind the search form about `app\models\UserProfile`.
 */
class UserProfile extends UserProfileModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'age',
								'category_id',
								'state_id',
								'type_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'height',
								'skills',
								'document_file',
								'education_qualification',
								'experience',
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
	/* public function getFileOption() {
		$list = self::getFileOptions ();
		return isset ( $list [$this->document_file] ) ? $list [$this->document_file] : 'Not Defined';
	} */
	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params        	
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = UserProfileModel::find ();
		
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
				'age' => $this->age,
				'category_id' => $this->category_id,
				'state_id' => $this->state_id,
				'type_id' => $this->type_id,
				'created_on' => $this->created_on,
				'updated_on' => $this->updated_on,
				'created_by_id' => $this->created_by_id 
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'height',
				$this->height 
		] )->andFilterWhere ( [ 
				'like',
				'skills',
				$this->skills 
		] )->andFilterWhere ( [ 
				'like',
				'document_file',
				$this->document_file 
		] )->andFilterWhere ( [ 
				'like',
				'education_qualification',
				$this->education_qualification 
		] )->andFilterWhere ( [ 
				'like',
				'experience',
				$this->experience 
		] );
		
		return $dataProvider;
	}
}
