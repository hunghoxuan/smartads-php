<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenContent;

/**
 * SmartscreenContent represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenContent`.
 */
class SmartscreenContentSearch extends SmartscreenContentBase
{
    public $order_by = "is_default desc, type asc";
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'duration', 'sort_order', 'is_active', 'is_default', 'created_date', 'modified_date'], 'integer'],
            [['title', 'url', 'description', 'type', 'kind', 'expire_date', 'owner_id', 'application_id'], 'safe'],
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
        $query = SmartscreenContent::find();

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
                'title' => $this->title,
                'url' => $this->url,
                'description' => $this->description,
                'type' => $this->type,
                'kind' => $this->kind,
                'duration' => $this->duration,
                'expire_date' => $this->expire_date,
                'sort_order' => $this->sort_order,
                'owner_id' => $this->owner_id,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'description' => $this->description,
                'kind' => $this->kind,
                'duration' => $this->duration,
                'expire_date' => $this->expire_date,
                'sort_order' => $this->sort_order,
                'owner_id' => $this->owner_id,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'url', $this->url])
                ->andFilterWhere(['like', 'type', $this->type]);
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