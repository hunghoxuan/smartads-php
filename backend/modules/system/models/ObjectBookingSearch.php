<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectBooking;

/**
 * ObjectBooking represents the model behind the search form about `backend\models\ObjectBooking`.
 */
class ObjectBookingSearch extends ObjectBooking
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['object_id', 'object_type', 'user_id', 'start_date', 'end_date', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = ObjectBooking::find();

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
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
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
            'object_type' => $this->object_type,
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'object_id', $this->object_id]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectBooking())->getOrderBy());

        return $dataProvider;
    }
}
