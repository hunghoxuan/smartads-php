<?php

namespace backend\modules\survey\models;

class SurveyResultAPI extends SurveyResultBase
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [
        'id',
        'survey_id',
        'question_id',
        'customer_id',
        'customer_info',
        'transaction_id',
        'comment',
        'answer',
        'branch_id',
        'employee_id',
        'ime'
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