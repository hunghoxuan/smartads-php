<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppUserTransaction;

/**
 * AppUserTransaction represents the model behind the search form about `backend\modules\app\models\AppUserTransaction`.
 */
class AppUserTransactionSearch extends AppUserTransaction{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['transaction_id', 'user_id', 'receiver_user_id', 'object_id', 'object_type', 'currency', 'payment_method', 'note', 'time', 'action', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
            [['amount'], 'number'],
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
        $query = AppUserTransaction::find();

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
            'transaction_id' => $this->transaction_id,
            'user_id' => $this->user_id,
            'receiver_user_id' => $this->receiver_user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'note' => $this->note,
            'time' => $this->time,
            'action' => $this->action,
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
            'receiver_user_id' => $this->receiver_user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'time' => $this->time,
            'type' => $this->type,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'action', $this->action]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUserTransaction())->getOrderBy());

        return $dataProvider;
    }
}
