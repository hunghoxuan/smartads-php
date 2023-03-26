<?php

namespace backend\modules\system\models;

class ObjectChangeAPI extends ObjectChangeBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'object_id',
        'object_type',
        'field_name',
        'old_value',
        'new_value',
        'change_date',
        'change_user',
        'note'
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