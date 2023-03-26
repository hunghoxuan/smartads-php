<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectType;

/**
 * ObjectType represents the model behind the search form about `backend\models\ObjectType`.
 */
class ObjectTypeSearch extends ObjectType{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_type', 'group', 'name'], 'safe'],
            [['sort_order', 'is_active', 'is_system'], 'integer'],
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
        $query = ObjectType::find();

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
            'object_type' => $this->object_type,
            'group' => $this->group,
            'name' => $this->name,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_system' => $this->is_system,
        ]);
        } else {
            $query->andFilterWhere([
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_system' => $this->is_system,
        ]);

        $query->andFilterWhere(['like', 'object_type', $this->object_type])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'name', $this->name]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectType())->getOrderBy());

        return $dataProvider;
    }
}
