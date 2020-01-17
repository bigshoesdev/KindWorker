<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JobImage as JobImageModel;

/**
 * JobImage represents the model behind the search form about `app\models\JobImage`.
 */
class JobImage extends JobImageModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'job_id', 'state_id', 'type_id', 'created_by_id'], 'integer'],
            [['image_file', 'created_on', 'updated_on'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function beforeValidate(){
            return true;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$id = null )
    {
    	if($id == null){
    		$query = JobImageModel::find();
    	}else{
    		$query = JobImageModel::find()->where(['job_id' => $id ]);
    	}
        

		        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
						'defaultOrder' => [
								'id' => SORT_DESC
						]
				]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'job_id' => $this->job_id,
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by_id' => $this->created_by_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file]);

        return $dataProvider;
    }
}
