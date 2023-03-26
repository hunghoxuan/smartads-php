<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;


use backend\modules\system\models\SettingsMenu;

/**
 * SettingsMenuSearch represents the model behind the search form about `backend\models\SettingsMenu`.
 */
class SettingsMenuSearch extends SettingsMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_order', 'is_active'], 'integer'],
            [['icon', 'name', 'url', 'object_type', 'module', 'group', 'role', 'menu_type', 'display_type', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'safe'],
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
        $query = SettingsMenu::find();

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
                'icon' => $this->icon,
                'name' => $this->name,
                'url' => $this->url,
                'object_type' => $this->object_type,
                'module' => $this->module,
                'group' => $this->group,
                'role' => $this->role,
                'menu_type' => $this->menu_type,
                'display_type' => $this->display_type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_type' => $this->object_type,
                'module' => $this->module,
                'group' => $this->group,
                'role' => $this->role,
                'menu_type' => $this->menu_type,
                'display_type' => $this->display_type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'icon', $this->icon])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'url', $this->url]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new SettingsMenu())->getOrderBy());

        return $dataProvider;
    }
}
