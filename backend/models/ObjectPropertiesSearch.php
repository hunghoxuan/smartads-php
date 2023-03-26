<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\models\ObjectProperties;

/**
 * ObjectProperties represents the model behind the search form about `backend\models\ObjectProperties`.
 */
class ObjectPropertiesSearch extends ObjectProperties{
    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];

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
        $query = ObjectProperties::find();

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
                'description' => $this->description,
                'content' => $this->content,
                'properties' => $this->properties,
                'translations' => $this->translations,
                'code' => $this->code,
                'title' => $this->title,
                'image' => $this->image,
                'type' => $this->type,
                'status' => $this->status,
                'category_id' => $this->category_id,
                'tags' => $this->tags,
                'keywords' => $this->keywords,
                'view_count' => $this->view_count,
                'download_count' => $this->download_count,
                'purchase_count' => $this->purchase_count,
                'like_count' => $this->like_count,
                'comment_count' => $this->comment_count,
                'edit_count' => $this->edit_count,
                'favourite_count' => $this->favourite_count,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
                'history' => $this->history,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'comments' => $this->comments,
                'address' => $this->address,
                'address2' => $this->address2,
                'mobile' => $this->mobile,
                'phone' => $this->phone,
                'email1' => $this->email1,
                'email2' => $this->email2,
                'coordinate' => $this->coordinate,
                'is_top' => $this->is_top,
                'is_active' => $this->is_active,
                'is_hot' => $this->is_hot,
                'is_new' => $this->is_new,
                'is_discount' => $this->is_discount,
                'is_vip' => $this->is_vip,
                'is_promotion' => $this->is_promotion,
                'is_expired' => $this->is_expired,
                'is_completed' => $this->is_completed,
                'author_name' => $this->author_name,
                'author_id' => $this->author_id,
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'provider_id' => $this->provider_id,
                'product_name' => $this->product_name,
                'provider_name' => $this->provider_name,
                'publisher_id' => $this->publisher_id,
                'publisher_name' => $this->publisher_name,
                'banner' => $this->banner,
                'thumbnail' => $this->thumbnail,
                'video' => $this->video,
                'link_url' => $this->link_url,
                'district' => $this->district,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zip_code' => $this->zip_code,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'content' => $this->content,
                'properties' => $this->properties,
                'translations' => $this->translations,
                'type' => $this->type,
                'status' => $this->status,
                'category_id' => $this->category_id,
                'view_count' => $this->view_count,
                'download_count' => $this->download_count,
                'purchase_count' => $this->purchase_count,
                'like_count' => $this->like_count,
                'comment_count' => $this->comment_count,
                'edit_count' => $this->edit_count,
                'favourite_count' => $this->favourite_count,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
                'history' => $this->history,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'comments' => $this->comments,
                'is_top' => $this->is_top,
                'is_active' => $this->is_active,
                'is_hot' => $this->is_hot,
                'is_new' => $this->is_new,
                'is_discount' => $this->is_discount,
                'is_vip' => $this->is_vip,
                'is_promotion' => $this->is_promotion,
                'is_expired' => $this->is_expired,
                'is_completed' => $this->is_completed,
                'author_id' => $this->author_id,
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'provider_id' => $this->provider_id,
                'publisher_id' => $this->publisher_id,
                'district' => $this->district,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
            ]);

            $query->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'tags', $this->tags])
                ->andFilterWhere(['like', 'keywords', $this->keywords])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'address2', $this->address2])
                ->andFilterWhere(['like', 'mobile', $this->mobile])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'email1', $this->email1])
                ->andFilterWhere(['like', 'email2', $this->email2])
                ->andFilterWhere(['like', 'coordinate', $this->coordinate])
                ->andFilterWhere(['like', 'author_name', $this->author_name])
                ->andFilterWhere(['like', 'product_name', $this->product_name])
                ->andFilterWhere(['like', 'provider_name', $this->provider_name])
                ->andFilterWhere(['like', 'publisher_name', $this->publisher_name])
                ->andFilterWhere(['like', 'banner', $this->banner])
                ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
                ->andFilterWhere(['like', 'video', $this->video])
                ->andFilterWhere(['like', 'link_url', $this->link_url])
                ->andFilterWhere(['like', 'zip_code', $this->zip_code]);
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