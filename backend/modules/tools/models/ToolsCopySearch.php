<?php

namespace backend\modules\tools\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\tools\models\ToolsCopy;

/**
 * ToolsCopy represents the model behind the search form about `backend\modules\tools\models\ToolsCopy`.
 */
class ToolsCopySearch extends ToolsCopyBase
{
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_user'], 'integer'],
            [['name', 'folders', 'files', 'description', 'created_date', 'modified_date', 'application_id'], 'safe'],
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
        $query = ToolsCopy::find();

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
            'folders' => $this->folders,
            'files' => $this->files,
            'description' => $this->description,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'folders' => $this->folders,
            'files' => $this->files,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'created_user' => $this->created_user,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'application_id', $this->application_id]);
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
