<?php

namespace backend\models;

use common\base\BaseAPIObject;
use common\components\FConfig;
use common\components\FHtml;

/**
 * Class SettingAPI
 * @package backend\models
 */
class SettingAPI extends Setting
{
    const DEFAULT_CURRENCY        = 'defaultCurrency';
    const TABLE_STRIP_LIGHT_COLOR = 'table_strip_light_color';
    const TABLE_STRIP_DARK_COLOR  = 'table_strip_dark_color';
    const TIMEZONE                = 'timezone';
    const BOTTOM_RUNNING_TEXT     = 'bottom_running_text';

}