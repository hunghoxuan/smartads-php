<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;

use common\components\FHtml;
use common\controllers\BaseApiController;

/**
 * Controller is the base class for RESTful API controller classes.
 *
 * Controller implements the following steps in a RESTful API request handling cycle:
 *
 * 1. Resolving response format (see [[ContentNegotiator]]);
 * 2. Validating request method (see [[verbs()]]).
 * 3. Authenticating user (see [[\yii\filters\auth\AuthInterface]]);
 * 4. Rate limiting (see [[RateLimiter]]);
 * 5. Formatting response data (see [[serializeData()]]).
 *
 * @author Hung Ho (Steve) | www.apptemplate.co, wwww.moza-tech.com, www.codeyii.com | skype: hung.hoxuan  <hung.hoxuan@gmail.com>
 * @since 2.0
 */
class ApiController extends BaseApiController
{
    public function actions()
    {
        $application_id = FHtml::currentApplicationId();
        $api_class = "applications\\$application_id\backend\controllers\ApiController";
        $actions = [];

        if (class_exists($api_class)) {
            $Object = \Yii::createObject(['class' => $api_class], [$this->id, $this->module]);
            $actions = isset($Object) ? $Object->actions() : [];
        }


        if (!empty($actions))
            return $actions;

        return array_merge(parent::actions(), [
            'home' => ['class' => 'backend\actions\HomeAction', 'checkAccess' => [$this, 'checkAccess']],
            'dashboard' => ['class' => 'backend\actions\DashboardAction', 'checkAccess' => [$this, 'checkAccess']],
            'default' => ['class' => 'backend\actions\HomeAction', 'checkAccess' => [$this, 'checkAccess']],
            'index' => ['class' => 'backend\actions\HomeAction', 'checkAccess' => [$this, 'checkAccess']],
            'settings' => ['class' => 'backend\actions\SettingsAction', 'checkAccess' => [$this, 'checkAccess']],
            'setting' => ['class' => 'backend\actions\SettingsAction', 'checkAccess' => [$this, 'checkAccess']],
            'category' => ['class' => 'backend\actions\CategoryAction', 'checkAccess' => [$this, 'checkAccess']],
            'file' => ['class' => 'backend\actions\ViewFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'image' => ['class' => 'backend\actions\ViewImageAction', 'checkAccess' => [$this, 'checkAccess']],
            'view-image' => ['class' => 'backend\actions\ViewImageAction', 'checkAccess' => [$this, 'checkAccess']],
            'update-location' => ['class' => 'backend\actions\UpdateLocationAction', 'checkAccess' => [$this, 'checkAccess']],
            'sendEmail' => ['class' => 'backend\actions\SendEmailAction', 'checkAccess' => [$this, 'checkAccess']],
            'send-email' => ['class' => 'backend\actions\SendEmailAction', 'checkAccess' => [$this, 'checkAccess']],
            'zipFile' => ['class' => 'backend\actions\ZipFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'zip-file' => ['class' => 'backend\actions\ZipFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'copyFile' => ['class' => 'backend\actions\CopyFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'copy-file' => ['class' => 'backend\actions\CopyFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'archive' => ['class' => 'backend\actions\ZipFileAction', 'checkAccess' => [$this, 'checkAccess']],
            'test' => ['class' => 'backend\actions\TestAction', 'checkAccess' => [$this, 'checkAccess']],
            'pushNotification' => ['class' => 'backend\actions\PushNotificationAction', 'checkAccess' => [$this, 'checkAccess']],
            'error-code' => ['class' => 'backend\actions\ErrorCodeAction', 'checkAccess' => [$this, 'checkAccess']],
        ]);
    }

    public function docs()
    {
        return $this->render('docs');
    }
}
