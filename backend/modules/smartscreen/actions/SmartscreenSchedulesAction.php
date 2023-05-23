<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\app\models\AppDeviceAPI;
use backend\modules\smartscreen\models\SmartscreenCampaigns;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConfig;
use common\components\FConstant;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use common\actions\BaseApiAction;
use common\components\FHtml;
use common\components\NodeJS;
use common\widgets\flogin\FLogin;


class SmartscreenSchedulesAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;
        Smartscreen::setDefaultTimezone();

        $ime = FHtml::getRequestParam(['ime']);
        $start_time = FHtml::getRequestParam(['current_time', 'start_time']);
        $date = FHtml::getRequestParam(['date']);

        $app_schedule_id = FHtml::getRequestParam(['current_schedule_id', 'schedule_id', 'finished_schedule_id']);
        $debug = FHtml::getRequestParam('debug', 0);
        $log = FHtml::getRequestParam('log');

        $channel_id = FHtml::getRequestParam(['channel_id']);;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : -1;
        $device_id = FHtml::getRequestParam(['device_id']);;

        $testDevices = FHtml::setting('smartscreen.test_devices');
        $isTestMode = (!empty($testDevices) && in_array($ime, explode(',', $testDevices)));

        if (empty($date))
            $date = date("Y-m-d");

        if (is_numeric($start_time) && $start_time > 0)
            $start_time = date("H:i", $start_time);
        else if (empty($start_time)) {
            $start_time = date('H:i');
        }

        if (empty($ime) && !empty($device_id)) {
            $device = SmartscreenStation::findOneCached($device_id);
            if (empty($ime) && isset($device))
                $ime = $device->ime;
        } else if (!empty($ime)) {
            $device = SmartscreenStation::findOneCached($ime);
        }

        if (empty($ime) || !isset($device)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, "Device ID is missing or not found", ['code' => 205]);
        }

        if (!empty($log)) {
            $logContent = Smartscreen::getDeviceLog($ime);
            echo $logContent;
            die;
        }

        if ($debug) {
            $result = null;
            $need_refresh = true;
        } else {
            $result = Smartscreen::getCacheDeviceSchedulesForAPI($ime);
            $need_refresh = Smartscreen::isDeviceNeedRefresh($ime, $result);
        }

        Smartscreen::setCacheDeviceLoadTime($ime);
        if (isset($device)) {
            $device->updateLastUpdate(); // avoid refresh schedules because save --> clear cache
            //            $device->last_update = time();
            //            $device->save();
        }

        if (!isset($result)) {
            $result = Smartscreen::getDeviceSchedulesForAPI($ime, $date, $start_time, $app_schedule_id, $channel_id, $app_schedule_id, $limit);
            if (is_string($result)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, $result, ['code' => 205]);
            }

            Smartscreen::setCacheDeviceSchedulesForAPI($ime, $result);
            $need_refresh = true;
        }

        //override cache date.
        $defaultSchedule  = $result['default_schedule'];
        $result['data'][0]['date'] = $date;
        $schedules = isset($result['data'][0]['schedules']) ? $result['data'][0]['schedules'] : [];


        if (empty($schedules)) {
            $schedule = Smartscreen::getDefaultSchedule($ime, "00:00", Smartscreen::getDurationBetween("00:00", 0, "24:00"));
            $schedules[] = $schedule;
        }
        //make sure api data is correct !!!
        foreach ($schedules as $i => $schedule) {
            $start_time_next = Smartscreen::getNextStartTime($schedule, null, 1, null, true);
            $schedule->end_time = $start_time_next;
            $schedule->id = (int) str_replace(":", "", $schedule->start_time);
            $schedule->date = $date;
        }

        $result['start_time'] = $start_time;
        $result['current_time'] = time();
        $result['need_refresh_schedules'] = $need_refresh;
        $result['name'] = isset($device) ? trim($device->name) : "";
        $result['description'] = isset($device) ? trim($device->description) : "";
        $result['license'] = isset($device) ? trim($device->LicenseKey) : "";

        $result['data'][0]['schedules'] = array_values($schedules); //update schedules

        return $result;
    }
}
