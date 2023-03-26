<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\SettingsLookup;

/**
 * SettingsLookup represents the model behind the search form about `backend\models\SettingsLookup`.
 */
class SettingsLookupSearch extends SettingsLookup{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_cached', 'is_active', 'sort_order'], 'integer'],
            [['name', 'object_type', 'params', 'fields', 'orderby', 'limit', 'sql', 'created_user', 'created_date', 'application_id'], 'safe'],
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
        $query = SettingsLookup::find();

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
            'name' => $this->name,
            'object_type' => $this->object_type,
            'params' => $this->params,
            'fields' => $this->fields,
            'orderby' => $this->orderby,
            'limit' => $this->limit,
            'sql' => $this->sql,
            'is_cached' => $this->is_cached,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_user' => $this->created_user,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_type' => $this->object_type,
            'is_cached' => $this->is_cached,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_user' => $this->created_user,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'fields', $this->fields])
            ->andFilterWhere(['like', 'orderby', $this->orderby])
            ->andFilterWhere(['like', 'limit', $this->limit])
            ->andFilterWhere(['like', 'sql', $this->sql]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new SettingsLookup())->getOrderBy());

        return $dataProvider;
    }
}
