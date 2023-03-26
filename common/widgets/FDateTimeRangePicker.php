<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use common\components\FHtml;
use common\components\FView;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\daterange\DateRangePickerAsset;
use kartik\daterange\LanguageAsset;
use kartik\daterange\MomentAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FDateTimeRangePicker extends FDateRangePicker
{
    public $format = '';
    public $disabled_date = '';
    public $disabled_hours = '';
    public $timePicker = true;
    public $timePickerIncrement = 30;

    public function init()
    {
        $this->presetDropdown = true;
        $this->hideInput = true;
        $this->convertFormat = true;
        if (empty($this->format))
            $this->format = FHtml::settingDateTimeFormat();

        $this->pluginOptions = array_merge($this->pluginOptions, [

                'timePicker'=> $this->timePicker,
                'timePickerIncrement'=> $this->timePickerIncrement,
                'locale'=>[
                    'format'=> $this->format
                ]
        ]);
        parent::init();
    }

    public function run()
    {
        return parent::run();
    }


}
