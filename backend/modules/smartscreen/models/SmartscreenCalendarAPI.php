<?php

namespace backend\modules\smartscreen\models;

class SmartscreenCalendarAPI extends SmartscreenCalendarBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'code',
        'name',
        'description',
        'date',
        'time',
        'device_id',
        'location',
        'type',
        'owner_name'
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