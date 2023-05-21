<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/03/2017
 * Time: 11:31 SA
 */

namespace applications\smartads;

use backend\models\AuthMenu;
use backend\modules\app\App;
use backend\modules\smartscreen\Smartscreen;
use backend\modules\survey\Survey;
use backend\modules\system\System;
use common\components\FApplication;
use common\components\FHtml;

use Yii;
use yii\web\User;

class Smartads extends FApplication
{
    const SETTINGS = [
        'smartads.default_duration' => 5,
        'smartads.override_device_name' => true,
        'smartads.save_device_name' => true,
        'smartads.default_device_status' => true,
        'smartads.his_api_server' => '10.0.0.94:8089',
        'smartads.his_api_patientslist' => '/api/wb/exam_pending?dept={dept_id}&room={room_id}',
        'smartads.his_api_departmentslist' => '/api/wb/deptlist',
        'smartads.his_api_roomslist' => '/api/wb/roomlist?dept={dept_id}',
        'smartads.page_refresh_interval' => 1000,
        'smartads.app_refresh_interval' => 10,

        'lcd.rowCount' => 4,
        'smartads.app_mode' => 'test',
        'smartads.start_time' => '06:00',
        'smartads.end_time' => '20:00'
    ];


    public static function getBackendMenu($controller = '', $action = '', $module = '')
    {

        $controller = FHtml::currentController();

        $menu[] = AuthMenu::buildDashBoardMenu();

        //$menu = array_merge($menu, Media::createModuleMenu());
        //$menu = array_merge($menu, \backend\modules\qms\Qms::createModuleMenu(['qms-branch-group', 'qms-branch', 'qms-services', 'qms-branch-station', 'qms-transactions']));

        $menu = array_merge($menu, Smartscreen::createModuleMenu([
            'smartscreen-campaigns',
            'smartscreen-schedules',
            //            'smartscreen-layouts',
            //            'smartscreen-frame',
            'smartscreen-content',
            'smartscreen-scripts'
        ]));

        $menu = array_merge($menu, Smartscreen::createModuleMenu(['smartscreen-channels', 'smartscreen-station'], 'Devices'));
        //$menu = array_merge($menu, Smartscreen::createModuleMenu(['smartscreen-queue', 'smartscreen-calendar'], 'HIS'));

        $menu = array_merge($menu, Smartscreen::createModuleMenu(['smartscreen-frame', 'smartscreen-layouts'], 'Layouts'));
        $menu = array_merge($menu, App::createModuleMenu(['app-file', 'app-version']));
        $menu = array_merge($menu, System::createModuleMenu(['user', 'settings', 'settings-text']));
        //$menu = array_merge($menu, Survey::createModuleMenu([]));

        //$menu = array_merge($menu, Smartscreen::createModuleMenu());

        return $menu;
    }
}
