<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppFile;

/**
 * AppFile represents the model behind the search form about `backend\modules\app\models\AppFile`.
 */
class AppFileSearch extends AppFileBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'file_size'], 'integer'],
            [['file_name', 'user_id', 'ime', 'status', 'download_time', 'created_date', 'application_id'], 'safe'],
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

    public function getDefaultFindParams()
    {
        $arr = [];
        $application_id = FHtml::getFieldValue($this, 'application_id');
        if (FHtml::isApplicationsEnabled($this->getTableName()) && !empty($application_id)) {
            $arr = ['application_id' => $application_id];
        }
        if (FHtml::field_exists($this, 'is_active') && FHtml::isRoleUser())
            $arr = array_merge($arr, ['is_active' => 1]);

        return $arr;
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
        $query = AppFile::find();

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
                'file_name' => $this->file_name,
                'file_size' => $this->file_size,
                'user_id' => $this->user_id,
                'ime' => $this->ime,
                'status' => $this->status,
                'download_time' => $this->download_time,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'file_size' => $this->file_size,
                'user_id' => $this->user_id,
                'status' => $this->status,
                'download_time' => $this->download_time,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'ime', $this->ime]);
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