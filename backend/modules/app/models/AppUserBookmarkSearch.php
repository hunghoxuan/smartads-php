<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppUserBookmark;

/**
 * AppUserBookmark represents the model behind the search form about `backend\modules\app\models\AppUserBookmark`.
 */
class AppUserBookmarkSearch extends AppUserBookmark{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['user_id', 'object_id', 'object_type', 'name', 'content', 'created_date', 'application_id'], 'safe'],
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
        $query = AppUserBookmark::find();

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
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'name' => $this->name,
            'content' => $this->content,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'content' => $this->content,
            'created_date' => $this->created_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUserBookmark())->getOrderBy());

        return $dataProvider;
    }
}
