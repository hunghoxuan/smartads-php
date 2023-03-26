<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenScripts;

/**
 * SmartscreenScripts represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenScripts`.
 */
class SmartscreenScriptsSearch extends SmartscreenScripts{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'Clipnum', 'CommandNumber', 'sort_order', 'is_active'], 'integer'],
            [['name', 'Logo', 'TopBanner', 'BotBanner', 'ClipHeader', 'ClipFooter', 'ScrollText', 'Clip1', 'Clip2', 'Clip3', 'Clip4', 'Clip5', 'Clip6', 'Clip7', 'Clip8', 'Clip9', 'Clip10', 'Clip11', 'Clip12', 'Clip13', 'Clip14', 'Line1', 'Line2', 'Line3', 'Line4', 'Line5', 'Line6', 'Line7', 'Line8', 'Line9', 'Line10', 'Line11', 'Line12', 'Line13', 'Line14', 'Line15', 'Line16', 'scripts_content', 'scripts_file', 'ReleaseDate', 'application_id'], 'safe'],
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
        $query = SmartscreenScripts::find();

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
            'Logo' => $this->Logo,
            'TopBanner' => $this->TopBanner,
            'BotBanner' => $this->BotBanner,
            'ClipHeader' => $this->ClipHeader,
            'ClipFooter' => $this->ClipFooter,
            'ScrollText' => $this->ScrollText,
            'Clipnum' => $this->Clipnum,
            'Clip1' => $this->Clip1,
            'Clip2' => $this->Clip2,
            'Clip3' => $this->Clip3,
            'Clip4' => $this->Clip4,
            'Clip5' => $this->Clip5,
            'Clip6' => $this->Clip6,
            'Clip7' => $this->Clip7,
            'Clip8' => $this->Clip8,
            'Clip9' => $this->Clip9,
            'Clip10' => $this->Clip10,
            'Clip11' => $this->Clip11,
            'Clip12' => $this->Clip12,
            'Clip13' => $this->Clip13,
            'Clip14' => $this->Clip14,
            'CommandNumber' => $this->CommandNumber,
            'Line1' => $this->Line1,
            'Line2' => $this->Line2,
            'Line3' => $this->Line3,
            'Line4' => $this->Line4,
            'Line5' => $this->Line5,
            'Line6' => $this->Line6,
            'Line7' => $this->Line7,
            'Line8' => $this->Line8,
            'Line9' => $this->Line9,
            'Line10' => $this->Line10,
            'Line11' => $this->Line11,
            'Line12' => $this->Line12,
            'Line13' => $this->Line13,
            'Line14' => $this->Line14,
            'Line15' => $this->Line15,
            'Line16' => $this->Line16,
            'scripts_content' => $this->scripts_content,
            'scripts_file' => $this->scripts_file,
            'ReleaseDate' => $this->ReleaseDate,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'ScrollText' => $this->ScrollText,
            'Clipnum' => $this->Clipnum,
            'CommandNumber' => $this->CommandNumber,
            'scripts_content' => $this->scripts_content,
            'ReleaseDate' => $this->ReleaseDate,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'Logo', $this->Logo])
            ->andFilterWhere(['like', 'TopBanner', $this->TopBanner])
            ->andFilterWhere(['like', 'BotBanner', $this->BotBanner])
            ->andFilterWhere(['like', 'ClipHeader', $this->ClipHeader])
            ->andFilterWhere(['like', 'ClipFooter', $this->ClipFooter])
            ->andFilterWhere(['like', 'Clip1', $this->Clip1])
            ->andFilterWhere(['like', 'Clip2', $this->Clip2])
            ->andFilterWhere(['like', 'Clip3', $this->Clip3])
            ->andFilterWhere(['like', 'Clip4', $this->Clip4])
            ->andFilterWhere(['like', 'Clip5', $this->Clip5])
            ->andFilterWhere(['like', 'Clip6', $this->Clip6])
            ->andFilterWhere(['like', 'Clip7', $this->Clip7])
            ->andFilterWhere(['like', 'Clip8', $this->Clip8])
            ->andFilterWhere(['like', 'Clip9', $this->Clip9])
            ->andFilterWhere(['like', 'Clip10', $this->Clip10])
            ->andFilterWhere(['like', 'Clip11', $this->Clip11])
            ->andFilterWhere(['like', 'Clip12', $this->Clip12])
            ->andFilterWhere(['like', 'Clip13', $this->Clip13])
            ->andFilterWhere(['like', 'Clip14', $this->Clip14])
            ->andFilterWhere(['like', 'Line1', $this->Line1])
            ->andFilterWhere(['like', 'Line2', $this->Line2])
            ->andFilterWhere(['like', 'Line3', $this->Line3])
            ->andFilterWhere(['like', 'Line4', $this->Line4])
            ->andFilterWhere(['like', 'Line5', $this->Line5])
            ->andFilterWhere(['like', 'Line6', $this->Line6])
            ->andFilterWhere(['like', 'Line7', $this->Line7])
            ->andFilterWhere(['like', 'Line8', $this->Line8])
            ->andFilterWhere(['like', 'Line9', $this->Line9])
            ->andFilterWhere(['like', 'Line10', $this->Line10])
            ->andFilterWhere(['like', 'Line11', $this->Line11])
            ->andFilterWhere(['like', 'Line12', $this->Line12])
            ->andFilterWhere(['like', 'Line13', $this->Line13])
            ->andFilterWhere(['like', 'Line14', $this->Line14])
            ->andFilterWhere(['like', 'Line15', $this->Line15])
            ->andFilterWhere(['like', 'Line16', $this->Line16])
            ->andFilterWhere(['like', 'scripts_file', $this->scripts_file]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
