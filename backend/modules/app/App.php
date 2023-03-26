<?php

namespace backend\modules\app;

use backend\models\AuthMenu;
use backend\models\User;
use common\components\FHtml;

/**
 * app module definition class
 */
class App extends \common\base\BaseModule
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

    public static function createModuleMenu($menus = ['app-user', 'app-device', 'app-token', 'app-notification'])
    {
        $controller = FHtml::currentController();

        $menu1[] = AuthMenu::menuItem(
            '#',
            'App',
            'glyphicon glyphicon-cog',
            FHtml::isInArray($controller, $menus),
            [User::ROLE_ADMIN],
            [
                !FHtml::isInArray('app-user', $menus) ?  null : AuthMenu::menuItem(
                    '/app/app-user/index',
                    'App Users',
                    'glyphicon glyphicon-wrench',
                    $controller == 'app-user',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('app-log', $menus) ?  null : AuthMenu::menuItem(
                    '/app/app-log/index',
                    'App Log',
                    'glyphicon glyphicon-wrench',
                    $controller == 'app-log',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('app-membership', $menus) ?  null : AuthMenu::menuItem(
                    '/app/app-membership/index',
                    'App Membership',
                    'glyphicon glyphicon-wrench',
                    $controller == 'app-membership',
                    [User::ROLE_ADMIN]
                )
                , !FHtml::isInArray('app-file', $menus) ?  null : AuthMenu::menuItem(
                '/app/app-file/index',
                'Download Files',
                'glyphicon glyphicon-wrench',
                $controller == 'app-file',
                [User::ROLE_ADMIN]
            ), !FHtml::isInArray('app-version', $menus) ?  null : AuthMenu::menuItem(
                '/app/app-version/index',
                'App Versions',
                'glyphicon glyphicon-wrench',
                $controller == 'app-version',
                [User::ROLE_ADMIN]
            ),
//                !FHtml::isInArray('app-device', $menus) ?  null : AuthMenu::menuItem(
//                    '/app/app-device/index',
//                    'App Devices',
//                    'glyphicon glyphicon-wrench',
//                    $controller == 'app-device',
//                    [User::ROLE_ADMIN]
//                ),
//                !FHtml::isInArray('app-token', $menus) ?  null : AuthMenu::menuItem(
//                    '/app/app-token/index',
//                    'App Tokens',
//                    'glyphicon glyphicon-wrench',
//                    $controller == 'app-token',
//                    [User::ROLE_ADMIN]
//                )
            ]
        );

        return $menu1;
    }

    public static function notifyAppVersionUpdate($ime = '', $action = '', $name = '', $description = '', $package_name = '', $package_version = '', $modified_date = '', $schedule_id = '', $channel_id = '') {
        if (empty($ime))
            $ime = 'all';

        if (is_object($ime)) {
            $ime = FHtml::getFieldValue($ime, ['ime', 'device_id']);
        }

        if (is_array($ime))
            $ime = implode(';', $ime);

        if (isset($action) && is_object($action)) {
            $model = $action;
            $action = 'app_version';
            $name = $model->name;
            $description = $model->description;
            $package_name = $model->package_name;
            $package_version = $model->package_version;
            $modified_date = $model->modified_date;
        }

        if (isset(\Yii::$app->redis)) {
            try {

                \Yii::$app->redis->executeCommand('PUBLISH', [
                    'channel' => 'notification',
                    'message' => Json::encode(['device' => $ime, 'auto' => true, 'action' => $action, 'name' => $name, 'description' => $description, 'package_name' => $package_name, 'package_version' => $package_version, 'modified_date' => $modified_date, 'schedule_id' => $schedule_id, 'channel_id' => $channel_id])
                ]);

                return true;
            } catch (Exception $e) {
                return FHtml::addError($e);
            }
        }
    }
}
