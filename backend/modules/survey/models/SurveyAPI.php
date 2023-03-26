<?php

namespace backend\modules\survey\models;

class SurveyAPI extends SurveyBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'name',
        'questions',
        'description',
        'date_start',
        'date_end',
        'is_active',
        'type',
        'status'
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

    private $data;
    public function getQuestions() {
        return $this->data;
    }

    public function setQuestions($value) {
        $this->data = $value;
    }
}