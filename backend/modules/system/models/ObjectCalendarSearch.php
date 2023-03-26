<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectCalendar;

/**
 * ObjectCalendar represents the model behind the search form about `backend\modules\system\models\ObjectCalendar`.
 */
class ObjectCalendarSearch extends ObjectCalendar{

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
        $query = ObjectCalendar::find();

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
                'color' => $this->color,
                'title' => $this->title,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'all_day' => $this->all_day,
                'status' => $this->status,
                'link_url' => $this->link_url,
                'type' => $this->type,
                'created_user' => $this->created_user,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'color' => $this->color,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'all_day' => $this->all_day,
                'status' => $this->status,
                'type' => $this->type,
                'created_user' => $this->created_user,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'link_url', $this->link_url]);
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