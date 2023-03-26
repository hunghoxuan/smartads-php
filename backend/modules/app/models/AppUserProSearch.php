<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppUserPro;

/**
 * AppUserPro represents the model behind the search form about `backend\modules\app\models\AppUserPro`.
 */
class AppUserProSearch extends AppUserPro{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'rate_count', 'is_active'], 'integer'],
            [['rate'], 'number'],
            [['description', 'business_name', 'business_email', 'business_address', 'business_website', 'business_phone', 'created_date', 'modified_date'], 'safe'],
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
        $query = AppUserPro::find();

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
            'user_id' => $this->user_id,
            'rate' => $this->rate,
            'rate_count' => $this->rate_count,
            'description' => $this->description,
            'business_name' => $this->business_name,
            'business_email' => $this->business_email,
            'business_address' => $this->business_address,
            'business_website' => $this->business_website,
            'business_phone' => $this->business_phone,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
        ]);
        } else {
            $query->andFilterWhere([
            'user_id' => $this->user_id,
            'rate' => $this->rate,
            'rate_count' => $this->rate_count,
            'business_phone' => $this->business_phone,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'business_name', $this->business_name])
            ->andFilterWhere(['like', 'business_email', $this->business_email])
            ->andFilterWhere(['like', 'business_address', $this->business_address])
            ->andFilterWhere(['like', 'business_website', $this->business_website]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUserPro())->getOrderBy());

        return $dataProvider;
    }
}
