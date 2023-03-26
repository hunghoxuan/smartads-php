<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;


use backend\modules\system\models\ObjectSetting;

/**
 * ObjectSettingSearch represents the model behind the search form about `backend\modules\system\models\ObjectSetting`.
 */
class ObjectSettingSearch extends ObjectSetting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'sort_order'], 'integer'],
            [['object_type', 'meta_key', 'key', 'value', 'description', 'icon', 'color', 'application_id'], 'safe'],
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
        $query = ObjectSetting::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'application_id' => $this->application_id
        ]);

        $query->andFilterWhere(['like', 'object_type', $this->object_type])
            ->andFilterWhere(['like', 'meta_key', $this->meta_key])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'color', $this->color]);

        $query->orderBy('object_type')->addOrderBy('meta_key')->addOrderBy('sort_order');

        return $dataProvider;
    }
}
