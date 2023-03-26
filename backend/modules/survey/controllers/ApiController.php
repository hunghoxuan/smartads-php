<?php

namespace backend\modules\survey\controllers;


class ApiController extends \backend\controllers\ApiController
{
    public function actions()
    {
        return [
            'list-questions' => ['class' => 'backend\modules\survey\actions\SurveyQuestionsAction', 'checkAccess' => [$this, 'checkAccess']],
            'add-result' => ['class' => 'backend\modules\survey\actions\SurveyResultAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }
}
