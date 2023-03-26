<?php

namespace backend\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\users\models\UserLogs;

/**
 * UserLogs represents the model behind the search form about `backend\modules\users\models\UserLogs`.
 */
class UserLogsSearch extends UserLogs{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['log_date', 'user_id', 'action', 'object_type', 'link_url', 'ip_address', 'duration', 'note', 'status', 'created_date', 'modified_date', 'application_id'], 'safe'],
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
        $query = UserLogs::find();

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
            'log_date' => $this->log_date,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'object_type' => $this->object_type,
            'object_id' => $this->object_id,
            'link_url' => $this->link_url,
            'ip_address' => $this->ip_address,
            'duration' => $this->duration,
            'note' => $this->note,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'log_date' => $this->log_date,
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
            'note' => $this->note,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'object_type', $this->object_type])
            ->andFilterWhere(['like', 'link_url', $this->link_url])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'status', $this->status]);
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
