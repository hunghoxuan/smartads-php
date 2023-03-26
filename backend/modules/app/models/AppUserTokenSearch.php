<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppUserToken;

/**
 * AppUserToken represents the model behind the search form about `backend\modules\app\models\AppUserToken`.
 */
class AppUserTokenSearch extends AppUserToken{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['token', 'time'], 'safe'],
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
        $query = AppUserToken::find();

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
            'token' => $this->token,
            'time' => $this->time,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'token' => $this->token,
            'time' => $this->time,
        ]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUserToken())->getOrderBy());

        return $dataProvider;
    }
}
