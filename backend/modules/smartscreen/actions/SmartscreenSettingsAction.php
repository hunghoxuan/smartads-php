<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\smartscreen\Smartscreen;
use common\actions\BaseApiAction;
use common\components\FApi;

class SmartscreenSettingsAction extends BaseApiAction
{
    public function run()
    {
        $settings = Smartscreen::getSettings();
        return FApi::getOutputForAPI($settings);
    }
}
