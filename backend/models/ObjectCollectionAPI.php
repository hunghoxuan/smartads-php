<?php

namespace backend\models;

class ObjectCollectionAPI extends ObjectCollectionBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'name',
        'description',
        'object_type',
        'is_active'
    ];

    public function fields()
    {
        $fields = $this::COLUMNS_API;

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}