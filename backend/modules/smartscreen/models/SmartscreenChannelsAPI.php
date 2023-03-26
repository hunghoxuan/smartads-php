<?php

namespace backend\modules\smartscreen\models;

class SmartscreenChannelsAPI extends SmartscreenChannelsBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'name',
        'description',
        'image',
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