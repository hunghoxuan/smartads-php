<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectFile;

/**
 * ObjectFile represents the model behind the search form about `backend\modules\system\models\ObjectFile`.
 */
class ObjectFileSearch extends ObjectFileBase
{
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'is_active', 'sort_order'], 'integer'],
            [['object_type', 'thumbnail', 'name', 'description', 'type', 'file', 'file_type', 'status', 'file_size', 'file_duration', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = ObjectFile::find();

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
            'thumbnail' => $this->thumbnail,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'file' => $this->file,
            'file_type' => $this->file_type,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'file_size' => $this->file_size,
            'file_duration' => $this->file_duration,
            'sort_order' => $this->sort_order,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'type' => $this->type,
            'file_type' => $this->file_type,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'file_size', $this->file_size])
            ->andFilterWhere(['like', 'file_duration', $this->file_duration]);
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
