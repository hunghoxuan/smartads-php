<?php

namespace backend\modules\tools\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\tools\models\ToolsImport;

/**
 * ToolsImport represents the model behind the search form about `backend\modules\tools\models\ToolsImport`.
 */
class ToolsImportSearch extends ToolsImport{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'first_row', 'last_row'], 'integer'],
            [['name', 'file', 'sheet_name', 'object_type', 'key_columns', 'columns', 'default_values', 'override_type', 'type', 'created_date', 'created_user', 'application_id'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ToolsImport::find();

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
            'file' => $this->file,
            'sheet_name' => $this->sheet_name,
            'first_row' => $this->first_row,
            'last_row' => $this->last_row,
            'object_type' => $this->object_type,
            'key_columns' => $this->key_columns,
            'columns' => $this->columns,
            'default_values' => $this->default_values,
            'override_type' => $this->override_type,
            'type' => $this->type,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'first_row' => $this->first_row,
            'last_row' => $this->last_row,
            'columns' => $this->columns,
            'default_values' => $this->default_values,
            'override_type' => $this->override_type,
            'type' => $this->type,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'sheet_name', $this->sheet_name])
            ->andFilterWhere(['like', 'object_type', $this->object_type])
            ->andFilterWhere(['like', 'key_columns', $this->key_columns]);
        }

        $application_id = FHtml::getFieldValue($this, 'application_id');
        if (FHtml::isApplicationsEnabled($this->getTableName()) && !empty($application_id)) {
            $query->andFilterWhere(['application_id' => $application_id]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
