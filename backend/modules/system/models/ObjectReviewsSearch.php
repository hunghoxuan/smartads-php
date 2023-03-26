<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectReviews;

/**
 * ObjectReviews represents the model behind the search form about `backend\models\ObjectReviews`.
 */
class ObjectReviewsSearch extends ObjectReviews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'user_id', 'is_active'], 'integer'],
            [['object_type', 'comment', 'name', 'email', 'created_date', 'application_id'], 'safe'],
            [['rate'], 'number'],
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
        $query = ObjectReviews::find();

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
            'rate' => $this->rate,
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'rate' => $this->rate,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectReviews())->getOrderBy());

        return $dataProvider;
    }
}
