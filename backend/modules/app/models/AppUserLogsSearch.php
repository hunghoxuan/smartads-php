<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppUserLogs;

/**
 * AppUserLogs represents the model behind the search form about `backend\modules\app\models\AppUserLogs`.
 */
class AppUserLogsSearch extends AppUserLogs{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'duration'], 'integer'],
            [['user_id', 'action', 'created_date', 'modified_date', 'application_id'], 'safe'],
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
        $query = AppUserLogs::find();

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
            'action' => $this->action,
            'duration' => $this->duration,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'duration' => $this->duration,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUserLogs())->getOrderBy());

        return $dataProvider;
    }
}
