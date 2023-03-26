<?php

namespace backend\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\users\models\UserFeedback;

/**
 * UserFeedback represents the model behind the search form about `backend\modules\users\models\UserFeedback`.
 */
class UserFeedbackSearch extends UserFeedback{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['user_id', 'object_id', 'object_type', 'name', 'email', 'comment', 'response', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = UserFeedback::find();

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
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'name' => $this->name,
            'email' => $this->email,
            'comment' => $this->comment,
            'is_active' => $this->is_active,
            'response' => $this->response,
            'type' => $this->type,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'is_active' => $this->is_active,
            'response' => $this->response,
            'type' => $this->type,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'comment', $this->comment]);
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
