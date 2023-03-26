<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectFavourites;

/**
 * ObjectFavourites represents the model behind the search form about `backend\models\ObjectFavourites`.
 */
class ObjectFavouritesSearch extends ObjectFavourites
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'user_id'], 'integer'],
            [['object_type', 'created_date', 'application_id'], 'safe'],
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
        $query = ObjectFavourites::find();

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
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'user_id' => $this->user_id,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new ObjectFavourites())->getOrderBy());

        return $dataProvider;
    }
}
