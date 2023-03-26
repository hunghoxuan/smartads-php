<?php

namespace backend\modules\system\models;

class ObjectContentAPI extends ObjectContentBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'object_id',
        'object_type',
        'image',
        'name',
        'description',
        'content',
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