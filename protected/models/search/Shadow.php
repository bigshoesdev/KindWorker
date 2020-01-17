<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Shadow as ShadowModel;

/**
 * Shadow represents the model behind the search form about `app\models\Shadow`.
 */
class Shadow extends ShadowModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'to_id', 'state_id', 'create_user_id'], 'integer'],
            [['create_time'], 'safe'],
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
        $query = ShadowModel::find();

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
            'to_id' => $this->to_id,
            'state_id' => $this->state_id,
            'create_time' => $this->create_time,
            'create_user_id' => $this->create_user_id,
        ]);

        return $dataProvider;
    }
}
