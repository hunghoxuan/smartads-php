<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\ObjectBanner;

/**
 * ObjectBanner represents the model behind the search form about `backend\modules\system\models\ObjectBanner`.
 */
class ObjectBannerSearch extends ObjectBanner{

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public static function createNew($values = [], $isSave = false) {
        return ObjectBanner::createNew();
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
            $models = ObjectBanner::findAll($params);
            return $dataProvider = new FActiveDataProvider([
            'models' => $models,
            ]);
        }

        $query = ObjectBanner::find();

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
                'image' => $this->image,
                'title' => $this->title,
                'link_url' => $this->link_url,
                'platform' => $this->platform,
                'position' => $this->position,
                'type' => $this->type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'platform' => $this->platform,
                'position' => $this->position,
                'type' => $this->type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'link_url', $this->link_url]);
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