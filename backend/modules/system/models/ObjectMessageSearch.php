<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectMessage;

/**
 * ObjectMessage represents the model behind the search form about `backend\modules\system\models\ObjectMessage`.
 */
class ObjectMessageSearch extends ObjectMessage{

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public static function createNew($values = [], $isSave = false) {
        return ObjectMessage::createNew();
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
        if (!static::isSqlDb()) {
            $models = ObjectMessage::findAll($params);
            return $dataProvider = new FActiveDataProvider([
            'models' => $models,
            ]);
        }

        $query = ObjectMessage::find();

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        //}

        if ($searchExact) {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'title' => $this->title,
                'message' => $this->message,
                'method' => $this->method,
                'send_date' => $this->send_date,
                'sender_id' => $this->sender_id,
                'sender_type' => $this->sender_type,
                'type' => $this->type,
                'status' => $this->status,
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
                'method' => $this->method,
                'send_date' => $this->send_date,
                'sender_id' => $this->sender_id,
                'sender_type' => $this->sender_type,
                'type' => $this->type,
                'status' => $this->status,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'message', $this->message]);
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}