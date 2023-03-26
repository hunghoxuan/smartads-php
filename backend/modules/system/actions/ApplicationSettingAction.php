<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use common\components\FHtml;


class ApplicationSettingAction extends BaseAction
{
    public function run()
    {
        $logo = FHtml::getCurrentLogoUrl();
        $currency = FHtml::getCurrentCurrency();
        return array(
            'logo' => $logo,
            'currency' => $currency
        );
    }
}