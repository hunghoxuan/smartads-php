<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectPeople;

/**
 * ObjectPeople represents the model behind the search form about `backend\models\ObjectPeople`.
 */
class ObjectPeopleSearch extends ObjectPeople
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_order', 'is_active'], 'integer'],
            [['object_id', 'object_type', 'name', 'title', 'phone', 'email', 'address', 'facebook', 'google', 'linkedin', 'skype', 'instagram', 'twitter', 'yahoo', 'application_id'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $andWhere = '')
    {
        $query = ObjectPeople::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($searchExact) {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'name' => $this->name,
            'title' => $this->title,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'facebook' => $this->facebook,
            'google' => $this->google,
            'linkedin' => $this->linkedin,
            'skype' => $this->skype,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'yahoo' => $this->yahoo,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'google', $this->google])
            ->andFilterWhere(['like', 'linkedin', $this->linkedin])
            ->andFilterWhere(['like', 'skype', $this->skype])
            ->andFilterWhere(['like', 'instagram', $this->instagram])
            ->andFilterWhere(['like', 'twitter', $this->twitter])
            ->andFilterWhere(['like', 'yahoo', $this->yahoo]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectPeople())->getOrderBy());

        return $dataProvider;
    }
}
