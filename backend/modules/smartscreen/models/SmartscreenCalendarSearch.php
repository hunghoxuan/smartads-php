<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenCalendar;

/**
 * SmartscreenCalendar represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenCalendar`.
 */
class SmartscreenCalendarSearch extends SmartscreenCalendarBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'name', 'description', 'content', 'date', 'time', 'device_id', 'location', 'type', 'owner_name', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = SmartscreenCalendar::find();

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
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'content' => $this->content,
                'date' => $this->date,
                'time' => $this->time,
                'device_id' => $this->device_id,
                'location' => $this->location,
                'type' => $this->type,
                'owner_name' => $this->owner_name,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'code' => $this->code,
                'date' => $this->date,
                'type' => $this->type,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'time', $this->time])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
                ->andFilterWhere(['like', 'location', $this->location])
                ->andFilterWhere(['like', 'owner_name', $this->owner_name]);
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