<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\app\models\AppDeviceAPI;
use backend\modules\smartscreen\models\SmartscreenScripts;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use common\actions\BaseApiAction;
use common\components\FHtml;


class SmartscreenScriptsAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : FHtml::getRequestParam('ScreenName');
        $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : null;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : FHtml::Now();
        $finished_schedule_id = isset($_REQUEST['finished_schedule_id']) ? $_REQUEST['finished_schedule_id'] : '';
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $channel_id = isset($_REQUEST['channel_id']) ? $_REQUEST['channel_id'] : '';
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : -1;
        $script_id = FHtml::getRequestParam('script_id');
        $format = FHtml::getRequestParam('format', 'xml');

        if (empty($ime)) {
            return Smartscreen::getOutputXML('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
        }

        $time = FHtml::getRequestParam(['time', 'Time']);

        $hash = FHtml::getRequestParam(['footprint', 'Footprint', 'FootPrint']);
        $station = FHtml::getRequestParam(['screenname', 'ScreenName', 'station', 'name'], $ime);

        //$checkFootPrint = Smartscreen::checkFootPrint($hash, $time, [$station, $time]);

        if (!empty($checkFootPrint))
            return $checkFootPrint;

        if (!empty($script_id)) {
            $scriptModel = SmartscreenScripts::getOne($script_id);
        } else {

            $scriptModel = Smartscreen::getDeviceScripts($station, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);

            if (!isset($scriptModel)) {
                $schedules = Smartscreen::getDeviceSchedules($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);

                if (is_string($schedules)) {
                    return Smartscreen::getOutputXML('', FConstant::ERROR, $schedules, ['code' => 205]);
                }

                $scriptModel = Smartscreen::buildSmartScreenScriptFromSchedules($schedules);
            }
        }

        if (isset($scriptModel))
            $result = Smartscreen::showXmlSmartScreenScript($scriptModel);
        else
            return Smartscreen::getOutputXML('', FConstant::ERROR, FConstant::DATA_NOT_FOUND, ['code' => 205]);

        $headers = \Yii::$app->response->headers;
        if ($format == 'xml')
            $headers->add('Content-Type', 'text/xml');

        return $result;
    }
}
