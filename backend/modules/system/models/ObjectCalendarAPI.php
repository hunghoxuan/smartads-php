<?php

namespace backend\modules\system\models;

class ObjectCalendarAPI extends ObjectCalendarBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'object_id',
        'object_type',
        'color',
        'title',
        'start_date',
        'end_date',
        'all_day',
        'status',
        'link_url',
        'type'
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