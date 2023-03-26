<?php

namespace backend\modules\smartscreen\models;

class SmartscreenStationAPI extends SmartscreenStationBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'name',
        'description',
        'ime',
        'status',
        'last_activity',
        'last_update',
        'ScreenName',
        'MACAddress',
        'LicenseKey',
        'branch_id',
        'channel_id',
        'dept_id',
        'room_id',
        'disk_storage'
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