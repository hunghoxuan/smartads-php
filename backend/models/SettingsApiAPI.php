<?php

namespace backend\models;

use common\components\FHtml;

class SettingsApiAPI extends SettingsApiSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'code',
        'name',
        'type',
        'data',
        'data_html',
        'data_link',
        'data_array',
        'data_array_columns',
        'permissions',
        'is_active'
    ];

    public function fields()
    {
        //required in order to dymanic set api fields
        if (!empty($this->api_fields))
            return $this->api_fields;

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