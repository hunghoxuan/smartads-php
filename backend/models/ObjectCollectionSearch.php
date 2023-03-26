<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\models\ObjectCollection;

/**
 * ObjectCollection represents the model behind the search form about `backend\models\ObjectCollection`.
 */
class ObjectCollectionSearch extends ObjectCollection{

	public function rules()
	{
		return [
			[['id','sort_order', 'is_active'], 'integer'],
			[[ 'name', 'description', 'object_type'], 'safe'],
		];
	}

    public function getDefaultFindParams()
    {
        $arr = [];
        $application_id = FHtml::getFieldValue($this, 'application_id');
        if (FHtml::isApplicationsEnabled($this->getTableName()) && !empty($application_id)) {
            $arr = ['application_id' => $application_id];
        }
        if (FHtml::field_exists($this, 'is_active') && FHtml::isRoleUser())
            $arr = array_merge($arr, ['is_active' => 1]);

        return $arr;
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
        $query = ObjectCollection::find();

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
                'description' => $this->description,
                'object_type' => $this->object_type,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_type' => $this->object_type,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description]);
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}