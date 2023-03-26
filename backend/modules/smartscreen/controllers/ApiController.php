<?php

namespace backend\modules\smartscreen\controllers;


class ApiController extends \backend\controllers\ApiController
{
    public function actions()
    {
        return [
            'schedules' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenSchedulesAction', 'checkAccess' => [$this, 'checkAccess']],
            'schedules-custom' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenSchedulesCustomAction', 'checkAccess' => [$this, 'checkAccess']],
            'schedules-ads' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenSchedulesAction', 'checkAccess' => [$this, 'checkAccess']],

            'content' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenContentAction', 'checkAccess' => [$this, 'checkAccess']],
            'cron-status-devices' => ['class' => 'backend\modules\smartscreen\actions\CronStatusDevices', 'checkAccess' => [$this, 'checkAccess']],
            'check-status-devices' => ['class' => 'backend\modules\smartscreen\actions\CheckStatusDevices', 'checkAccess' => [$this, 'checkAccess']],
            'registerDevice' => ['class' => 'backend\modules\smartscreen\actions\RegisterDeviceAction', 'checkAccess' => [$this, 'checkAccess']],
            'file-download' => ['class' => 'backend\modules\smartscreen\actions\FileDownloadAction', 'checkAccess' => [$this, 'checkAccess']],
            'app-version' => ['class' => 'backend\modules\smartscreen\actions\AppVersionAction', 'checkAccess' => [$this, 'checkAccess']],
            'his-update' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenHisUpdateAction', 'checkAccess' => [$this, 'checkAccess']],
            'script' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenScriptsAction', 'checkAccess' => [$this, 'checkAccess']],
            'check-update' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenScriptsAction', 'checkAccess' => [$this, 'checkAccess']],
            'time-sync' => ['class' => 'backend\modules\smartscreen\actions\TimeSyncAction', 'checkAccess' => [$this, 'checkAccess']],
            'his-content' => ['class' => 'backend\modules\smartscreen\actions\HisContentAction', 'checkAccess' => [$this, 'checkAccess']],
            'settings' => ['class' => 'backend\modules\smartscreen\actions\SmartscreenSettingsAction', 'checkAccess' => [$this, 'checkAccess']],

        ];
    }
}
