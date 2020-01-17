<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use app\models\WorkerSkill as WorkerSkillModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubCategory;
use app\models\Category;
use app\models\User;
use app\models\Slot;

/**
 * WorkerSkill represents the model behind the search form about `app\models\WorkerSkill`.
 */
class WorkerSkill extends WorkerSkillModel {
	/**
	 * @inheritdoc
	 */
	public $zipcode;
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								'category_id',
                                'sub_category_id',
								'type_id'
						],
						'integer' 
				],
				[ 
						[ 
								'zipcode',
								'description',
								'hourly_rate',
								'created_on',
								'updated_on',
								'sub_category_id',
								'experience',
								'created_by_id',
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
	public function getCreatedBy() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'created_by_id' 
		] );
	}
	public function getSubCategory() {
		return $this->hasOne ( SubCategory::className (), [ 
				'id' => 'sub_category_id' 
		] );
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
		$query = WorkerSkillModel::find ()->alias ( 'w' )->joinWith ( 'category as c' )->joinWith ( 'subCategory as sb' )->joinWith ( 'createdBy as cb' );
		
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
				'w.id' => $this->id,
				'w.experience' => $this->experience 
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'w.description',
				$this->description 
		] )->andFilterWhere ( [ 
				'like',
				'w.hourly_rate',
				$this->hourly_rate 
		] )->andFilterWhere ( [ 
				'like',
				'c.title',
				$this->category_id 
		] )->andFilterWhere ( [ 
				'like',
				'sb.title',
				$this->sub_category_id 
		] )->andFilterWhere ( [ 
				'like',
				'cb.first_name',
				$this->created_by_id 
		] )->andFilterWhere ( [ 
				'like',
				'w.experience',
				$this->experience 
		] );
		
		return $dataProvider;
	}
    public function filterWorkerSkill($params, $page = 0) {

        $ids = [];
        $ids1 = [];
        $user = \Yii::$app->user->identity;
        $lat = $user->latitude;
        $long = $user->longitude;
        $distance = $params['Filter']['distance'];
        if ($lat && $long) {
            $subQuery = User::find ()->select ( "*,( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) -
			radians({$long}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) AS distance" )->having ( "distance <:distance" )->addParams ( [
                ':distance' => $distance
            ] )->all();//->orderBy ( 'distance ASC' )->all ();

            if (! empty ( $subQuery )) {
                foreach ( $subQuery as $sub ) {
                    $ids [] = $sub->id;
                }
            }
            if(!empty($ids))
            {
                $filter_rating = floatval($params['Filter']['rating']);
                foreach ($ids as $id)
                {
                    $rating = floatval($this->getWorkerAvgRating($id));
                    if($rating>=$filter_rating && $id!=$user->id)
                    {
                        $ids1[] = $id;
                    }
                }
            }

            $subQuery = Slot::find ()->andWhere([
                'day_id'=>$params['Availability']['day_id']
            ])->andFilterWhere([
                'in',
                'created_by_id',
                $ids1
            ])->all ();
            $ids1 = [ ];
            if (! empty ( $subQuery )) {
                foreach ( $subQuery as $sub ) {
                    $times = explode( ',', $sub->times);
                    $index = intval($params['Availability']['hour']);
                    if(isset($times[$index]) && $times[$index] == '1'){
                        $ids1[]=$sub->created_by_id;
                    }
                }
            }
        }

	    $query = WorkerSkill::find ()->alias ( 'w' )->joinWith ( 'createdBy as u' )->andFilterWhere ( [
            'in',
            'w.created_by_id',
            $ids1
        ] )->andWhere([
            'u.vacation_mode'=>User::VACATION_OFF
        ])->andWhere ( [
				'w.state_id' => WorkerSkill::STATE_ACTIVE,
				'w.type_id' => intval($params['Filter']['service_type']),
        ] )->andFilterWhere ( [
            '<=',
            'w.hourly_rate',
            intval($params ['Filter'] ['hourly_rate'])
        ] )->andFilterWhere ( [
            '>=',
            'w.experience',
            intval($params ['Filter'] ['experience'])
        ] )->andFilterWhere ( [
				'=',
				'w.sub_category_id',
				intval($params ['Filter'] ['sub_category_id'])
		] );
        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ] );
		return $dataProvider;
	}

    public function searchWorkerSkill($params, $page = null) {
        $ids = [];
        $subQuery = Slot::find ()->andWhere([
            'day_id'=>$params['Availability']['day_id']
        ])->all ();
        if (! empty ( $subQuery )) {
            foreach ( $subQuery as $sub ) {
                $times = explode( ',', $sub->times);
                $index = intval($params['Availability']['hour']);
                if(isset($times[$index]) && $times[$index] == '1'){
                    $ids[]=$sub->created_by_id;
                }
            }
        }

	    $query = WorkerSkill::find ()->alias ( 'w' )->joinWith ( 'createdBy as u' )->joinWith ( 'category as c' )->joinWith ( 'subCategory as sc' )->andWhere ( [
            'w.state_id' => WorkerSkill::STATE_ACTIVE,
            'w.type_id' => Category::ONLINE_SERIVICE
        ] );
        $query->andWhere([
            'in',
            'w.created_by_id',
            $ids
        ]);
        $query->andWhere([
            'u.vacation_mode'=>User::VACATION_OFF
        ]);
        $query->andFilterWhere ( [
            'OR',
            [
                'like',
                'sc.title',
                $params ['WorkerSkill'] ['search']
            ],
            [
                'like',
                'c.title',
                $params ['WorkerSkill'] ['search']
            ]
        ] );

        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ]
        ] );
        return $dataProvider;
    }
    public function searchView($params) {

        $query = WorkerSkill::find ()->alias ( 'w' )->joinWith ( 'category as c' )->joinWith ( 'subCategory as sc' )->andWhere ( [
            'w.created_by_id' => $params['id'],
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

	public function searchWorkerSkillLocal($params, $page = null, $lat, $long, $date = null) {
        $ids = [ ];
        if ($lat && $long) {
            $subQuery = User::find ()->select ( "*,( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) -
			radians({$long}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) AS distance" )->having ( "distance <:distance" )->addParams ( [
                ':distance' => '40'
            ] )->orderBy ( 'distance ASC' )->all ();

            if (! empty ( $subQuery )) {
                foreach ( $subQuery as $sub ) {
                    $ids [] = $sub->id;
                }
            }

            $subQuery = Slot::find ()->andWhere([
                'day_id'=>$params['Availability']['day_id']
            ])->andFilterWhere([
                'in',
                'created_by_id',
                $ids
            ])->all ();
            $ids = [ ];
            if (! empty ( $subQuery )) {
                foreach ( $subQuery as $sub ) {
                    $times = explode( ',', $sub->times);
                    $index = intval($params['Availability']['hour']);
                    if(isset($times[$index]) && $times[$index] == '1'){
                        $ids[]=$sub->created_by_id;
                    }
                }
            }
        }
        $query = WorkerSkill::find ()->alias ( 'w' )->joinWith ( 'createdBy as u' )->joinWith ( 'category as c' )->joinWith ( 'subCategory as sc' )->andWhere ( [
            'in',
            'w.created_by_id',
            $ids
        ] );
        $query->andWhere([
            'u.vacation_mode'=>User::VACATION_OFF
        ]);
        $query->andWhere ( [
            'w.state_id' => WorkerSkill::STATE_ACTIVE,
            'w.type_id' => Category::LOCAL_SERVICE
        ] );
        $query->andFilterWhere ( [
            'OR',
            [
                'like',
                'sc.title',
                $params ['WorkerSkill'] ['search']
            ],
            [
                'like',
                'c.title',
                $params ['WorkerSkill'] ['search']
            ]
        ] );

        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ]
        ] );
        return $dataProvider;
	}
}

