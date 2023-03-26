<?php

namespace backend\modules\smartscreen\actions;


use backend\models\ObjectFileAPI;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenContentAPI;
use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenFileAPI;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\actions\BaseApiAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;
use Yii;


class SmartscreenContentAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
        $finished_schedule_id = isset($_REQUEST['finished_schedule_id']) ? $_REQUEST['finished_schedule_id'] : '';
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
        $content_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $content_id;

        $channel_id = isset($_REQUEST['channel_id']) ? $_REQUEST['channel_id'] : '';

        // if (empty($ime)) {
        //     return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
        // }

        $list_audio = null;
        $data = null;

        if ($content_id != null && !empty($content_id)) {
            $content = SmartscreenContent::findOne($content_id);
            if (isset($content))
                $data  = Smartscreen::getContentData($content, $ime);
        } else {

            $schedule = Smartscreen::getDeviceCurrentSchedule($ime, $start_time, $finished_schedule_id, $channel_id);

            if (is_string($schedule) || empty($schedule) || !isset($schedule)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, $schedule, ['code' => 205]);
            }

            if (is_array($schedule)) {
                $schedule = $schedule[0]["schedules"][0];
            }

            $data = Smartscreen::getScheduleData($schedule);

            $list_audio = Smartscreen::getScheduleBackgroundAudio($schedule);
        }

        $result = FApi::getOutputForAPI($data, FConstant::SUCCESS, 'OK', ['code' => 200]);
        $result['audio'] = $list_audio;

        // $result['id'] = $schedule->id;
        // $result['device_id'] = $schedule->device_id;
        // $result['start_time'] = $schedule->start_time;
        // $result['date'] = $schedule->date;
        // $result['duration'] = FHtml::getNumeric($schedule->duration);

        return $result;
    }
}
