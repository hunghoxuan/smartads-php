<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\models\Setting;

/**
 * Setting represents the model behind the search form about `backend\models\Setting`.
 */
class SettingSearch extends Setting{

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public static function createNew($values = [], $isSave = false) {
        return Setting::createNew();
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
            $models = Setting::findAll($params);
            return $dataProvider = new FActiveDataProvider([
            'models' => $models,
            ]);
        }

        $query = Setting::find();

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
                'metaKey' => $this->metaKey,
                'metaValue' => $this->metaValue,
                'group' => $this->group,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'metaValue' => $this->metaValue,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'metaKey', $this->metaKey])
                ->andFilterWhere(['like', 'group', $this->group]);
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