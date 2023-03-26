<?php

namespace backend\modules\survey\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\survey\models\Survey;

/**
 * Survey represents the model behind the search form about `backend\modules\survey\models\Survey`.
 */
class SurveySearch extends Survey{
    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];

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
        $query = Survey::find();

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        //}

        if ($searchExact) {
            $query->andFilterWhere([
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'date_start' => $this->date_start,
                'date_end' => $this->date_end,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'date_start' => $this->date_start,
                'date_end' => $this->date_end,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
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