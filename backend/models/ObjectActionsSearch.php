<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\models\ObjectActions;

/**
 * ObjectActions represents the model behind the search form about `backend\models\ObjectActions`.
 */
class ObjectActionsSearch extends ObjectActions{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['object_id', 'object_type', 'name', 'old_content', 'content', 'action', 'created_date', 'created_user', 'application_id'], 'safe'],
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
        $query = ObjectActions::find();

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
            'name' => $this->name,
            'old_content' => $this->old_content,
            'content' => $this->content,
            'action' => $this->action,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'old_content' => $this->old_content,
            'content' => $this->content,
            'action' => $this->action,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
