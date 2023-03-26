<?php

namespace backend\modules\app\models;

use common\components\FHtml;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppNotification represents the model behind the search form about `backend\modules\app\models\AppNotification`.
 */
class AppNotificationSearch extends AppNotification{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'receiver_count'], 'integer'],
            [['message', 'action', 'params', 'sent_type', 'sent_date', 'receiver_users', 'created_date', 'created_user', 'application_id'], 'safe'],
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
        $query = AppNotification::find();

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
            'message' => $this->message,
            'action' => $this->action,
            'params' => $this->params,
            'sent_type' => $this->sent_type,
            'sent_date' => $this->sent_date,
            'receiver_count' => $this->receiver_count,
            'receiver_users' => $this->receiver_users,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'sent_type' => $this->sent_type,
            'sent_date' => $this->sent_date,
            'receiver_count' => $this->receiver_count,
            'receiver_users' => $this->receiver_users,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'params', $this->params]);
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
