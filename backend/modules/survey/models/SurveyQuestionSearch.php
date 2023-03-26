<?php

namespace backend\modules\survey\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\survey\models\SurveyQuestion;

/**
 * SurveyQuestion represents the model behind the search form about `backend\modules\survey\models\SurveyQuestion`.
 */
class SurveyQuestionSearch extends SurveyQuestion{
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
        $query = SurveyQuestion::find();

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
                'image' => $this->image,
                'content' => $this->content,
                'type' => $this->type,
                'allow_comment' => $this->allow_comment,
                'timeout' => $this->timeout,
                'hint' => $this->hint,
                'answers' => $this->answers,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'content' => $this->content,
                'type' => $this->type,
                'allow_comment' => $this->allow_comment,
                'timeout' => $this->timeout,
                'hint' => $this->hint,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'answers', $this->answers]);
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