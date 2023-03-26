<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;


use backend\modules\app\models\AppUser;

/**
 * AppUserSearch represents the model behind the search form about `backend\modules\app\models\AppUser`.
 */
class AppUserSearch extends AppUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'point', 'rate_count', 'is_online', 'is_active', 'role'], 'integer'],
            [['avatar', 'name', 'username', 'email', 'password', 'auth_key', 'password_hash', 'password_reset_token', 'description', 'content', 'gender', 'dob', 'phone', 'weight', 'height', 'address', 'country', 'state', 'city', 'card_number', 'card_cvv', 'card_exp', 'lat', 'long', 'type', 'status', 'created_date', 'modified_date'], 'safe'],
            [['balance', 'rate'], 'number'],
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
        $query = AppUser::find();

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
                'avatar' => $this->avatar,
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'auth_key' => $this->auth_key,
                'password_hash' => $this->password_hash,
                'password_reset_token' => $this->password_reset_token,
                'description' => $this->description,
                'content' => $this->content,
                'gender' => $this->gender,
                'dob' => $this->dob,
                'phone' => $this->phone,
                'weight' => $this->weight,
                'height' => $this->height,
                'address' => $this->address,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'balance' => $this->balance,
                'point' => $this->point,
                'card_number' => $this->card_number,
                'card_cvv' => $this->card_cvv,
                'card_exp' => $this->card_exp,
                'lat' => $this->lat,
                'long' => $this->long,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'is_online' => $this->is_online,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'role' => $this->role,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'auth_key' => $this->auth_key,
                'content' => $this->content,
                'gender' => $this->gender,
                'phone' => $this->phone,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'balance' => $this->balance,
                'point' => $this->point,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'is_online' => $this->is_online,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'role' => $this->role,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
            ]);

            $query->andFilterWhere(['like', 'avatar', $this->avatar])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'dob', $this->dob])
                ->andFilterWhere(['like', 'weight', $this->weight])
                ->andFilterWhere(['like', 'height', $this->height])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'card_number', $this->card_number])
                ->andFilterWhere(['like', 'card_cvv', $this->card_cvv])
                ->andFilterWhere(['like', 'card_exp', $this->card_exp])
                ->andFilterWhere(['like', 'lat', $this->lat])
                ->andFilterWhere(['like', 'long', $this->long]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new AppUser())->getOrderBy());

        return $dataProvider;
    }
}
