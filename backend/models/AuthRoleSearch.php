<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\models\AuthRole;

/**
 * AuthRole represents the model behind the search form about `backend\models\AuthRole`.
 */
class AuthRoleSearch extends AuthRole{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['code', 'name', 'description', 'created_date', 'modified_date', 'application_id'], 'safe'],
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
        $query = AuthRole::find();

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
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'code' => $this->code,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
