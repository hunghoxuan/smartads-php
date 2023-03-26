<?php

namespace backend\modules\survey\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\survey\models\SurveyResult;

/**
 * SurveyResult represents the model behind the search form about `backend\modules\survey\models\SurveyResult`.
 */
class SurveyResultSearch extends SurveyResult{
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
        $query = SurveyResult::find();

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
                'survey_id' => $this->survey_id,
                'question_id' => $this->question_id,
                'customer_id' => $this->customer_id,
                'customer_info' => $this->customer_info,
                'transaction_id' => $this->transaction_id,
                'comment' => $this->comment,
                'answer' => $this->answer,
                'branch_id' => $this->branch_id,
                'employee_id' => $this->employee_id,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
                'ime' => $this->ime,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'survey_id' => $this->survey_id,
                'question_id' => $this->question_id,
                'customer_id' => $this->customer_id,
                'customer_info' => $this->customer_info,
                'created_date' => $this->created_date,
            ]);

            $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
                ->andFilterWhere(['like', 'comment', $this->comment])
                ->andFilterWhere(['like', 'answer', $this->answer])
                ->andFilterWhere(['like', 'branch_id', $this->branch_id])
                ->andFilterWhere(['like', 'employee_id', $this->employee_id])
                ->andFilterWhere(['like', 'application_id', $this->application_id])
                ->andFilterWhere(['like', 'ime', $this->ime]);
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