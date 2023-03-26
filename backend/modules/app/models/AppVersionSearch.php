<?php

namespace backend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\app\models\AppVersion;

/**
 * AppVersion represents the model behind the search form about `backend\modules\app\models\AppVersion`.
 */
class AppVersionSearch extends AppVersionBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'package_version', 'count_views', 'count_downloads', 'is_active', 'is_default'], 'integer'],
            [['name', 'description', 'package_name', 'platform', 'platform_info', 'file', 'history', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = AppVersion::find();

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
                'name' => $this->name,
                'description' => $this->description,
                'package_version' => $this->package_version,
                'package_name' => $this->package_name,
                'platform' => $this->platform,
                'platform_info' => $this->platform_info,
                'file' => $this->file,
                'count_views' => $this->count_views,
                'count_downloads' => $this->count_downloads,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
                'history' => $this->history,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'package_version' => $this->package_version,
                'count_views' => $this->count_views,
                'count_downloads' => $this->count_downloads,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
                'history' => $this->history,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'package_name', $this->package_name])
                ->andFilterWhere(['like', 'platform', $this->platform])
                ->andFilterWhere(['like', 'platform_info', $this->platform_info])
                ->andFilterWhere(['like', 'file', $this->file]);
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