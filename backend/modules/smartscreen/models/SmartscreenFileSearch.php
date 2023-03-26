<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenFile;

/**
 * SmartscreenFile represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenFile`.
 */
class SmartscreenFileSearch extends SmartscreenFileBase
{
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'is_active', 'sort_order'], 'integer'],
            [['file', 'description', 'file_kind', 'file_size', 'file_duration', 'created_date', 'created_user', 'application_id'], 'safe'],
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

    public function getDefaultFindParams() {
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
        $query = SmartscreenFile::find();

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
            'file' => $this->file,
            'description' => $this->description,
            'file_kind' => $this->file_kind,
            'file_size' => $this->file_size,
            'file_duration' => $this->file_duration,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'file_kind' => $this->file_kind,
            'file_duration' => $this->file_duration,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_size', $this->file_size]);
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
