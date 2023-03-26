<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\Application;

/**
 * Application represents the model behind the search form about `backend\modules\system\models\Application`.
 */
class ApplicationSearch extends Application{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'storage_max', 'storage_current', 'is_active'], 'integer'],
            [['logo', 'code', 'name', 'description', 'keywords', 'note', 'lang', 'modules', 'address', 'map', 'website', 'email', 'phone', 'fax', 'chat', 'facebook', 'twitter', 'google', 'youtube', 'copyright', 'terms_of_service', 'profile', 'privacy_policy', 'type', 'status', 'owner_id', 'created_date', 'created_user', 'modified_date', 'modified_user'], 'safe'],
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

        $query = Application::find();

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
            'logo' => $this->logo,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'note' => $this->note,
            'lang' => $this->lang,
            'modules' => $this->modules,
            'storage_max' => $this->storage_max,
            'storage_current' => $this->storage_current,
            'address' => $this->address,
            'map' => $this->map,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'chat' => $this->chat,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'google' => $this->google,
            'youtube' => $this->youtube,
            'copyright' => $this->copyright,
            'terms_of_service' => $this->terms_of_service,
            'profile' => $this->profile,
            'privacy_policy' => $this->privacy_policy,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'status' => $this->status,
            'owner_id' => $this->owner_id,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
            'modified_user' => $this->modified_user,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'lang' => $this->lang,
            'storage_max' => $this->storage_max,
            'storage_current' => $this->storage_current,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'status' => $this->status,
            'owner_id' => $this->owner_id,
            'created_date' => $this->created_date,
            'created_user' => $this->created_user,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'modules', $this->modules])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'map', $this->map])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'chat', $this->chat])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'twitter', $this->twitter])
            ->andFilterWhere(['like', 'google', $this->google])
            ->andFilterWhere(['like', 'youtube', $this->youtube])
            ->andFilterWhere(['like', 'copyright', $this->copyright])
            ->andFilterWhere(['like', 'terms_of_service', $this->terms_of_service])
            ->andFilterWhere(['like', 'profile', $this->profile])
            ->andFilterWhere(['like', 'privacy_policy', $this->privacy_policy])
            ->andFilterWhere(['like', 'modified_user', $this->modified_user]);
        }

        if (key_exists('application_id', $params))
            $application_id = FHtml::getFieldValue($params, 'application_id');
        else
            $application_id = FHtml::getFieldValue($this, 'application_id');

        if (FHtml::isApplicationsEnabled() && !empty($application_id)) {
            $query->andFilterWhere(['code' => $application_id]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
