<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenFrame;

/**
 * SmartscreenFrame represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenFrame`.
 */
class SmartscreenFrameSearch extends SmartscreenFrameBase
{
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'layout_id', 'percentWidth', 'percentHeight', 'marginTop', 'marginLeft', 'created_date', 'modified_date', 'content_id', 'sort_order', 'is_active'], 'integer'],
            [['name', 'backgroundColor', 'contentLayout', 'application_id', 'file', 'content', 'font_size', 'font_color', 'alignment'], 'safe'],
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

    public function getDefaultFindParams() {
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
        $query = SmartscreenFrame::find();

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
            'backgroundColor' => $this->backgroundColor,
            'layout_id' => $this->layout_id,
            'percentWidth' => $this->percentWidth,
            'percentHeight' => $this->percentHeight,
            'marginTop' => $this->marginTop,
            'marginLeft' => $this->marginLeft,
            'contentLayout' => $this->contentLayout,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
            'file' => $this->file,
            'content' => $this->content,
            'content_id' => $this->content_id,
            'font_size' => $this->font_size,
            'font_color' => $this->font_color,
            'alignment' => $this->alignment,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'backgroundColor' => $this->backgroundColor,
            'layout_id' => $this->layout_id,
            'percentWidth' => $this->percentWidth,
            'percentHeight' => $this->percentHeight,
            'marginTop' => $this->marginTop,
            'marginLeft' => $this->marginLeft,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'content' => $this->content,
            'content_id' => $this->content_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'contentLayout', $this->contentLayout])
            ->andFilterWhere(['like', 'application_id', $this->application_id])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'font_size', $this->font_size])
            ->andFilterWhere(['like', 'font_color', $this->font_color])
            ->andFilterWhere(['like', 'alignment', $this->alignment]);
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
