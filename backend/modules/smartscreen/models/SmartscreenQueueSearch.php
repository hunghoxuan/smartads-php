<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenQueue;

/**
 * SmartscreenQueue represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenQueue`.
 */
class SmartscreenQueueSearch extends SmartscreenQueueBase
{
    public $order_by = 'device_id asc, sort_order asc, name asc';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'sort_order'], 'integer'],
            [['code', 'name', 'ticket', 'counter', 'service', 'service_id', 'status', 'note', 'device_id', 'created_date', 'created_user', 'application_id', 'description'], 'safe'],
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
        $query = SmartscreenQueue::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        if ($searchExact) {
            $query->andFilterWhere([
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'ticket' => $this->ticket,
                'counter' => $this->counter,
                'service' => $this->service,
                'service_id' => $this->service_id,
                'status' => $this->status,
                'note' => $this->note,
                'device_id' => $this->device_id,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
                'description' => $this->description,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'service_id' => $this->service_id,
                'status' => $this->status,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'ticket', $this->ticket])
                ->andFilterWhere(['like', 'counter', $this->counter])
                ->andFilterWhere(['like', 'service', $this->service])
                ->andFilterWhere(['like', 'note', $this->note])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
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