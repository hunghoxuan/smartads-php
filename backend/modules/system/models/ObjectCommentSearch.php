<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectComment;

/**
 * ObjectComment represents the model behind the search form about `backend\models\ObjectComment`.
 */
class ObjectCommentSearch extends ObjectComment {
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],
            [['object_id', 'object_type', 'comment', 'app_user_id', 'user_id', 'user_type', 'created_date', 'created_user', 'application_id'], 'safe'],
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
        $query = ObjectComment::find();

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
            'parent_id' => $this->parent_id,
            'comment' => $this->comment,
            'app_user_id' => $this->app_user_id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_type' => $this->object_type,
            'parent_id' => $this->parent_id,
            'app_user_id' => $this->app_user_id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'object_id', $this->object_id])
            ->andFilterWhere(['like', 'comment', $this->comment]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectComment())->getOrderBy());

        return $dataProvider;
    }
}
