<?php

namespace backend\models;

use common\components\FActiveDataProvider;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\models\Settings;

/**
 * Settings represents the model behind the search form about `backend\models\Settings`.
 */
class SettingsSearch extends Settings {
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_system'], 'integer'],
            [['metaKey', 'metaValue', 'group', 'editor', 'lookup', 'application_id'], 'safe'],
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
    public function search($params)
    {
        if (!is_object(Settings::getDb())) {

            $models = Settings::findAll($params);
            return $dataProvider = new FActiveDataProvider([
                'models' => $models,
            ]);
        }

        $query = Settings::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
            'metaKey' => $this->metaKey,
            'metaValue' => $this->metaValue,
            'group' => $this->group,
            'editor' => $this->editor,
            'lookup' => $this->lookup,
            'is_system' => $this->is_system,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'is_system' => $this->is_system,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'metaKey', $this->metaKey])
            ->andFilterWhere(['like', 'metaValue', $this->metaValue])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'editor', $this->editor])
            ->andFilterWhere(['like', 'lookup', $this->lookup]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby('group');

        return $dataProvider;
    }
}
