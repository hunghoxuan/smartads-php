<?php

namespace backend\models;

use common\components\FHtml;

class ObjectPropertiesAPI extends ObjectPropertiesSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'object_id',
        'object_type',
        'description',
        'content',
        'properties',
        'translations',
        'code',
        'title',
        'image',
        'type',
        'status',
        'category_id',
        'tags',
        'keywords',
        'view_count',
        'download_count',
        'purchase_count',
        'like_count',
        'comment_count',
        'edit_count',
        'favourite_count',
        'history',
        'rate',
        'rate_count',
        'comments',
        'address',
        'address2',
        'mobile',
        'phone',
        'email1',
        'email2',
        'coordinate',
        'is_top',
        'is_active',
        'is_hot',
        'is_new',
        'is_discount',
        'is_vip',
        'is_promotion',
        'is_expired',
        'is_completed',
        'author_name',
        'author_id',
        'user_id',
        'product_id',
        'provider_id',
        'product_name',
        'provider_name',
        'publisher_id',
        'publisher_name',
        'banner',
        'thumbnail',
        'video',
        'link_url',
        'district',
        'city',
        'state',
        'country',
        'zip_code'
    ];

    public function fields()
    {
        $fields = $this::COLUMNS_API;
        foreach (self::COLUMNS_UPLOAD as $field) {
            $this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
        }
        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}