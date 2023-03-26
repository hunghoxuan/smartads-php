<?php

namespace backend\modules\users\controllers;

// use ApiController;

/**
 * Default controller for the `api` module
 */
class ApiController extends \backend\controllers\ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'home' => ['class' => 'backend\modules\users\actions\HomeAction', 'checkAccess' => [$this, 'checkAccess']],
            'user-feedback' => ['class' => 'backend\modules\users\actions\UserFeedbackAction', 'checkAccess' => [$this, 'checkAccess']],
            'user-logs' => ['class' => 'backend\modules\users\actions\UserLogsAction', 'checkAccess' => [$this, 'checkAccess']],

        ];
    }

}
