<?php

namespace backend\modules\smartscreen\models;

class SmartscreenQueueAPI extends SmartscreenQueueBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'code',
        'name',
        'ticket',
        'counter',
        'service',
        'service_id',
        'status',
        'note',
        'device_id',
        'is_active',
        'description'
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