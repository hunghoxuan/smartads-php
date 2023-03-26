<?php

namespace backend\models;

use common\components\FActiveDataProvider;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\models\SettingsText;

/**
 * SettingsText represents the model behind the search form about `backend\modules\system\models\SettingsText`.
 */
class SettingsTextSearch extends SettingsText {
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'lang', 'content', 'application_id', 'group'], 'safe'],
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
        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        if (!is_object(SettingsText::getDb())) {

            $models = SettingsText::findAll($params);
            return $dataProvider = new FActiveDataProvider([
                'models' => $models,
            ]);
        }

        $query = SettingsText::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($searchExact) {
            $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'lang' => $this->lang,
            'content' => $this->content,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'content', $this->content]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;

    }

}
