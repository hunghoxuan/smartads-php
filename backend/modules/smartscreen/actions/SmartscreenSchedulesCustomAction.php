<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\app\models\AppDeviceAPI;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use common\actions\BaseApiAction;
use common\components\FHtml;


class SmartscreenSchedulesCustomAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : null;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
        $finished_schedule_id = isset($_REQUEST['finished_schedule_id']) ? $_REQUEST['finished_schedule_id'] : '';
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $channel_id = isset($_REQUEST['channel_id']) ? $_REQUEST['channel_id'] : '';
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : -1;

        if (empty($ime)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
        }

        $schedules = Smartscreen::getDeviceSchedules($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);

        $result = $schedules;
        $default_schedule = Smartscreen::getDefaultSchedule();

        $files = Smartscreen::getDeviceScheduleFiles($result);
        $files1 = Smartscreen::getDeviceScheduleFiles($default_schedule);
        $files = array_merge($files, $files1);

        if (is_string($result)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $result, ['code' => 205]);
        }

        //$result = [Smartscreen::getStandBySchedule()];

        $result = FApi::getOutputForAPI($result, FConstant::SUCCESS, 'OK', ['code' => 200]);

        $result['start_time'] = $start_time;
        $result['current_time'] = time();

        $result['download_files'] = $files;

        $result['download_time'] = "";

        $result['default_schedule'] = $default_schedule;

        $result['settings'] = Smartscreen::getSettings();

        return $result;
    }
}
