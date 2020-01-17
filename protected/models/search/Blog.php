<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Blog represents the model behind the search form about `app\models\Blog`.
 */
class Blog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'view_count', 'state_id', 'type_id', 'create_user_id'], 'integer'],
            [['title', 'content', 'keywords', 'thumbnail_file', 'create_time', 'update_time'], 'safe'],
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
    public function search($params)
    {
        $query = Blog::find();

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
            'view_count' => $this->view_count,
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'create_user_id' => $this->create_user_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'thumbnail_file', $this->thumbnail_file]);

        return $dataProvider;
    }
}
