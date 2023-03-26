<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\SettingsSchema;

/**
 * SettingsSchema represents the model behind the search form about `backend\models\SettingsSchema`.
 */
class SettingsSchemaSearch extends SettingsSchema{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_order', 'is_group', 'is_column', 'is_readonly', 'is_active', 'is_system'], 'integer'],
            [['object_type', 'name', 'description', 'dbType', 'editor', 'lookup', 'format', 'algorithm', 'group', 'roles'], 'safe'],
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
    public function search($params, $andWhere = '')
    {
        $query = SettingsSchema::find();

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
            'object_type' => $this->object_type,
            'name' => $this->name,
            'description' => $this->description,
            'dbType' => $this->dbType,
            'editor' => $this->editor,
            'lookup' => $this->lookup,
            'format' => $this->format,
            'algorithm' => $this->algorithm,
            'group' => $this->group,
            'roles' => $this->roles,
            'sort_order' => $this->sort_order,
            'is_group' => $this->is_group,
            'is_column' => $this->is_column,
            'is_readonly' => $this->is_readonly,
            'is_active' => $this->is_active,
            'is_system' => $this->is_system,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_type' => $this->object_type,
            'dbType' => $this->dbType,
            'editor' => $this->editor,
            'sort_order' => $this->sort_order,
            'is_group' => $this->is_group,
            'is_column' => $this->is_column,
            'is_readonly' => $this->is_readonly,
            'is_active' => $this->is_active,
            'is_system' => $this->is_system,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'lookup', $this->lookup])
            ->andFilterWhere(['like', 'format', $this->format])
            ->andFilterWhere(['like', 'algorithm', $this->algorithm])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'roles', $this->roles]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new SettingsSchema())->getOrderBy());

        return $dataProvider;
    }
}
