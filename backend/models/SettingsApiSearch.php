<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\models\SettingsApi;

/**
 * SettingsApi represents the model behind the search form about `backend\modules\system\models\SettingsApi`.
 */
class SettingsApiSearch extends SettingsApi {
    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];

        return $arr;
    }

    public static function createNew($values = [], $saved = false) {
        return new SettingsApiSearch();
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
        if (!is_object(static::getDb())) {
            $models = SettingsApi::findAll($params);

            return $dataProvider = new FActiveDataProvider([
                'models' => $models,
            ]);
        }

        $query = SettingsApi::find();

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
                'code' => $this->code,
                'name' => $this->name,
                'type' => $this->type,
                'data' => $this->data,
                'data_html' => $this->data_html,
                'data_link' => $this->data_link,
                'data_array' => $this->data_array,
                'data_array_columns' => $this->data_array_columns,
                'permissions' => $this->permissions,
                'is_active' => $this->is_active,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'type' => $this->type,
                'data' => $this->data,
                'data_html' => $this->data_html,
                'data_array' => $this->data_array,
                'data_array_columns' => $this->data_array_columns,
                'permissions' => $this->permissions,
                'is_active' => $this->is_active,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'data_link', $this->data_link]);
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