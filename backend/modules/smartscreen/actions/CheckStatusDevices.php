<?php
namespace backend\modules\smartscreen\actions;

use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use common\actions\BaseApiAction;
use common\components\FEmail;
use common\components\FError;
use common\components\FHtml;
use common\components\NodeJS;
use yii\db\Exception;

class CheckStatusDevices extends BaseApiAction
{
    public function run()
    {
        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $activity = isset($_REQUEST['activity']) ? $_REQUEST['activity'] : '';
        $schedule_id = isset($_REQUEST['current_schedule']) ? $_REQUEST['current_schedule'] : '';
        $free_disk_size = isset($_REQUEST['free_disk_size']) ? $_REQUEST['free_disk_size'] : '';
        $log = isset($_REQUEST['log']) ? $_REQUEST['log'] : '';

        if (empty($ime)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FError::MISSING_PARAMS, ['code' => 205]);
        }

        $device = SmartscreenStation::findOneCached($ime);
        if (isset($device)) {
            try {
                $device->last_activity = $activity;
                $device->last_update = time();
                $device->disk_storage = $free_disk_size;
                $device->save();
            } catch (Exception $ex) {
                /* ignore */
            }
        }
        if (!empty($log)) {
            Smartscreen::addDeviceLog($ime, $log, date('Y-m-d'), date('H:i'));
        }
        Smartscreen::setCacheDeviceLoadTime($ime);
        return FApi::getOutputForAPI(null, FConstant::SUCCESS, 'OK', [
            'data' => $device,
            //'log' => Smartscreen::getDeviceLog($ime),
            'code' => 200,
            'need_refresh_schedules' => Smartscreen::isDeviceNeedRefresh($ime)]);
    }
}
