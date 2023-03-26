<?php
namespace backend\models;

class UtilityCountryAPI extends UtilityCountryBase
{
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function rules()
    {
        return [];
    }
}