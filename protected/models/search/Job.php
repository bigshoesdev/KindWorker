<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Job as JobModel;

/**
 * Job represents the model behind the search form about `app\models\Job`.
 */
class Job extends JobModel {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'id',
								
						],
						'integer' 
				],
				[ 
						[ 
								'description',
								'title',
								'total_price',
								'slot_id',
								'estimated_price',
								'first_file',
								'second_file',
								'third_file',
								'address',
								'latitude',
								'longitude',
								'date',
								'created_on',
								'updated_on' ,
								'worker_id',
								'category_id',
								'sub_category_id',
								'customjob_skills',
								'budget_type',
								'gig_quantity',
								'status',
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
		$query = JobModel::find ()->alias('j')->joinWith('createdBy as cb')->joinWith('category as c')->joinWith('subCategory as sc');
		
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
				'j.id' => $this->id,
				'j.state_id' => $this->state_id,
		] );
		
		$query->andFilterWhere ( [
				'like',
				'c.title',
				$this->category_id
		] )->andFilterWhere ( [ 
				'like',
				'sc.title',
				$this->sub_category_id
		] )->andFilterWhere ( [ 
				'like',
				'j.title',
				$this->title 
		] )->andFilterWhere ( [ 
				'like',
				'j.estimated_price',
				$this->estimated_price 
		] )->andFilterWhere ( [ 
				'like',
				'j.address',
				$this->address 
		] )->andFilterWhere ( [ 
				'like',
				'cb.first_name',
				$this->created_by_id 
		] );
		
		return $dataProvider;
	}
	public function searchCustomJobList($skills, $page=null)
    {

        $models = JobModel::find ()->alias('j')->andWhere([
            'j.job_type' => Job::JOB_TYPE_CUSTOM,
            'j.state_id' => Job::STATE_IN_BID_PROGRESS])->andFilterWhere([
                '!=',
                'j.created_by_id',
                Yii::$app->user->id
        ])->all();
        $job_ids = [];
        foreach ($models as $model) {
            $required_skills = explode(',', $model->customjob_skills);
            $flag = true;
            foreach ($required_skills as $required_skill) {
                if(empty($required_skill))
                    break;
                $pos = strpos($skills,$required_skill);
                if($pos===false) {
                    $flag = false;
                    break;
                }
            }
            if($flag == true)
            {
                $bid = Bid::find()->andWhere([
                    'job_id'=>$model->id,
                    'created_by_id'=>$user_id = Yii::$app->user->id
                ])->one();
                if(empty($bid))
                    $job_ids[] = $model->id;
            }
        }
        $query = JobModel::find ()->alias ( 'j' )->andWhere ( [
            'in',
            'j.id',
            $job_ids
        ] );
        $dataProvider = new ActiveDataProvider ( [
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC
                ]
            ]
        ] );
        return $dataProvider;
    }

    public function searchCustomJob($params) {
		
		$query = JobModel::find ()->alias('j')->where(['job_type' => Job::JOB_TYPE_CUSTOM])->joinWith('createdBy as cb')->joinWith('category as c')->joinWith('subCategory as sc');

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
				'j.id' => $this->id,
				'j.state_id' => $this->state_id,
		] );
		
		$query->andFilterWhere ( [
				'like',
				'c.title',
				$this->category_id
		] )->andFilterWhere ( [
				'like',
				'sc.title',
				$this->sub_category_id
		] )->andFilterWhere ( [
				'like',
				'j.title',
				$this->title
		] )->andFilterWhere ( [
				'like',
				'j.estimated_price',
				$this->estimated_price
		] )->andFilterWhere ( [
				'like',
				'j.address',
				$this->address
		] )->andFilterWhere ( [
				'like',
				'cb.first_name',
				$this->created_by_id
		] );
		
		return $dataProvider;
	}
	public function searchBookedWorker($params) {
		$query = JobModel::find ()->alias('j')->where(['job_type' => Job::JOB_TYPE_BOOOKED])->joinWith('worker as w')->joinWith('createdBy as cb')->joinWith('category as c')->joinWith('subCategory as sc');
		
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
				'j.id' => $this->id,
				'j.state_id' => $this->state_id,
		] );
		
		$query->andFilterWhere ( [
				'like',
				'c.title',
				$this->category_id
		] )->andFilterWhere ( [
				'like',
				'sc.title',
				$this->sub_category_id
		] )->andFilterWhere ( [
				'like',
				'j.title',
				$this->title
		] )->andFilterWhere ( [
				'like',
				'j.estimated_price',
				$this->estimated_price
		] )->andFilterWhere ( [
				'like',
				'j.address',
				$this->address
		] )->andFilterWhere ( [
				'like',
				'cb.first_name',
				$this->created_by_id
		] );
		
		return $dataProvider;
	}
	public function searchWorkerLocal($params, $page = null, $lat, $long, $date = null) {
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
		}
		$query = Job::find ()->alias ( 'j' )->joinWith ( 'averageRating as ar' )->andWhere ( [
				'in',
				'j.worker_id',
				$ids
		] );
		$query->where ( [
				'j.state_id' => Job::STATE_COMPLETE
		] );
		$query->andFilterWhere ( [
				
				[
						'like',
						'ar.rate',
						$this->rate
						
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
