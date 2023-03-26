<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\models\ObjectFile;

/**
 * ObjectFile represents the model behind the search form about `backend\models\ObjectFile`.
 */
class ObjectFileSearch extends ObjectFile{

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public static function createNew($values = [], $isSave = false) {
        return ObjectFile::createNew();
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
            $models = ObjectFile::findAll($params);
            return $dataProvider = new FActiveDataProvider([
            'models' => $models,
            ]);
        }

        $query = ObjectFile::find();

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
                'file' => $this->file,
                'title' => $this->title,
                'description' => $this->description,
                'file_type' => $this->file_type,
                'file_size' => $this->file_size,
                'file_duration' => $this->file_duration,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'file_type' => $this->file_type,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'file', $this->file])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'file_size', $this->file_size])
                ->andFilterWhere(['like', 'file_duration', $this->file_duration]);
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