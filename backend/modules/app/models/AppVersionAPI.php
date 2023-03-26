<?php

namespace backend\modules\app\models;

class AppVersionAPI extends AppVersionBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'name',
        'description',
        'version_code',
        'version_name',
        'min_sdk_version',
        'target_sdk_version',
        'file',
        'count_views',
        'count_downloads',
        'is_active',
        'is_default'
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