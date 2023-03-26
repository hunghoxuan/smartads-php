<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use common\components\FHtml;
use common\components\FView;
use kartik\base\WidgetAsset;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\daterange\DateRangePickerAsset;
use kartik\daterange\LanguageAsset;
use kartik\daterange\MomentAsset;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FDateRangePicker extends DateRangePicker
{
    public $format = '';
    public $disabled_date = '';
    public $disabled_hours = '';
    public $timePicker = false;
    public $timePickerIncrement = 30;
    public $label = '<span class="left-ind">{pickerIcon}</span>';
    public $border = true;

    public $containerTemplate = <<< HTML
        <div class="kv-drp-dropdown">
            {label}
            <input type="text" readonly class="form-control range-value" style="{style}" value="{value}">
            <span class="right-ind"><b class="caret"></b></span>
        </div>
        {input}
HTML;


    protected function initRange()
    {
//        $this->startAttribute = 'date_start';
//        $this->endAttribute = 'date_end';

        $this->pluginOptions['autoApply'] = false;
        $this->pluginOptions['linkedCalendars'] = true;
        $this->pluginOptions['alwaysShowCalendars'] = true;

        if (isset($dummyValidation)) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $msg = \Yii::t('kvdrp', 'Select Date Range');
        }
        if ($this->presetDropdown) {
            $m = 'moment()';
            $this->initRangeExpr = $this->hideInput = true;
            $this->pluginOptions['opens'] = ArrayHelper::getValue($this->pluginOptions, 'opens', 'left');
            $beg = "{$m}.startOf('day')";
            $end = "{$m}.endOf('day')";
            $last = "{$m}.subtract(1, 'month')";
            $this->pluginOptions['ranges'] = [
                \Yii::t('kvdrp', 'Empty') => ['1900-01-01', "{$m}.endOf('year').add(100, 'years')"],
                \Yii::t('kvdrp', 'Today') => [$beg, $end],
                \Yii::t('kvdrp', 'Yesterday') => ["{$beg}.subtract(1,'days')", "{$end}.subtract(1,'days')"],
                \Yii::t('kvdrp', 'Last {n} Days', ['n' => 7]) => ["{$beg}.subtract(6, 'days')", $end],
                \Yii::t('kvdrp', 'Last {n} Days', ['n' => 14]) => ["{$beg}.subtract(13, 'days')", $end],
                \Yii::t('kvdrp', 'This Month') => ["{$m}.startOf('month')", "{$m}.endOf('month')"],
                \Yii::t('kvdrp', 'Last Month') => ["{$last}.startOf('month')", "{$last}.endOf('month')"],
                \Yii::t('kvdrp', 'Last {n} Months', ['n' => 3]) => ["{$m}.subtract(3, 'month').startOf('month')", $end],
                \Yii::t('kvdrp', 'This Year') => ["{$last}.startOf('year')", "{$last}.endOf('year')"],
                //\Yii::t('kvdrp', 'Last Year') => ["{$m}.startOf('year')->subtract(1, 'year')", "{$m}.endOf('year')->subtract(1, 'year')"],


            ];
            if (empty($this->value)) {
                $this->pluginOptions['startDate'] = new JsExpression("{$m}.startOf('day')");
                $this->pluginOptions['endDate'] = new JsExpression($m);
            }
        }
        $opts = $this->pluginOptions;
        if (!$this->initRangeExpr || empty($opts['ranges']) || !is_array($opts['ranges'])) {
            return;
        }
        $range = [];
        foreach ($opts['ranges'] as $key => $value) {
            if (!is_array($value)) {
                throw new InvalidConfigException(
                    "Invalid settings for pluginOptions['ranges']. Each range value must be a two element array."
                );
            }
            $range[$key] = [static::parseJsExpr($value[0]), static::parseJsExpr($value[1])];
        }
        $this->pluginOptions['ranges'] = $range;
    }

    public function init()
    {
        $this->containerTemplate = strtr($this->containerTemplate, [
            '{label}' => isset($this->label) ? $this->label : '<span class="left-ind">{pickerIcon}</span>',
            '{pickerIcon}' => $this->pickerIcon,
            '{style}' => $this->border ? '' : 'border: none !important;'
        ]);

        $this->presetDropdown = true;
        $this->hideInput = true;
        $this->convertFormat = true;
        if (empty($this->format))
            $this->format = FHtml::settingDateFormat();

        $this->pluginOptions['timePicker'] = $this->timePicker;
        $this->pluginOptions['readonly'] = false;


        $this->pluginOptions['timePickerIncrement'] = $this->timePickerIncrement;
        $this->pluginOptions['locale']['format'] = $this->format;
        parent::init();
    }

    public function run()
    {
        return parent::run();
    }

    public function registerAssets()
    {
        $view = $this->getView();

        MomentAsset::register($view);
        $input = 'jQuery("#' . $this->options['id'] . '")';
        $id = $input;
        if ($this->hideInput) {
            $id = 'jQuery("#' . $this->containerOptions['id'] . '")';
        }
        if (!empty($this->_langFile)) {
            LanguageAsset::register($view)->js[] = $this->_langFile;
        }
        DateRangePickerAsset::register($view);
        $rangeJs = '';
        if (empty($this->callback)) {
            $val = "start.format('{$this->_format}') + '{$this->_separator}' + end.format('{$this->_format}')";
            if (ArrayHelper::getValue($this->pluginOptions, 'singleDatePicker', false)) {
                $val = "start.format('{$this->_format}')";
            }
            $rangeJs = $this->getRangeJs('start') . $this->getRangeJs('end');
            $change = "{$input}.val(val).trigger('change');{$rangeJs}";
            if ($this->presetDropdown) {
                $id = "{$id}.find('.kv-drp-dropdown')";
            }
            if ($this->hideInput) {
                $script = "var val={$val};{$id}.find('.range-value').val(val);{$change}";
            } elseif ($this->useWithAddon) {
                $id = "{$input}.closest('.input-group')";
                $script = "var val={$val};{$change}";
            } elseif (!$this->autoUpdateOnInit) {
                $script = "var val={$val};{$change}";
            } else {
                $this->registerPlugin($this->pluginName, $id);
                return;
            }
            $this->callback = "function(start,end,label){{$script}}";
        }
        $nowFrom = "moment().startOf('day').format('{$this->_format}')";
        $nowTo = "moment().format('{$this->_format}')";
        // parse input change correctly when range input value is cleared
        $js = <<< JS
{$input}.off('change.kvdrp').on('change.kvdrp', function() {
    var drp = {$id}.data('{$this->pluginName}'), fm, to;
    if ($(this).val() || !drp) {
        return;
    }
    fm = {$nowFrom} || '';
    to = {$nowTo} || '';
    drp.setStartDate(fm);
    drp.setEndDate(to);
    {$rangeJs}
});
JS;
        if ($this->presetDropdown && empty($this->value)) {
            $js .= "var val={$nowFrom}+'{$this->_separator}'+{$nowTo};{$id}.find('.range-value').val(val);";
        }
        $view->registerJs($js);
        $view->registerJs($js, FView::POS_AJAX_COMPLETE); //load after ajax as well

        $this->registerPlugin($this->pluginName, $id, null, $this->callback);
    }

    /**
     * Registers a JS code block for the widget.
     *
     * @param string $js the JS code block to be registered
     * @param integer $pos the position at which the JS script tag should be inserted in a page. The possible values
     * are:
     * - [[View::POS_HEAD]]: in the head section
     * - [[View::POS_BEGIN]]: at the beginning of the body section
     * - [[View::POS_END]]: at the end of the body section
     * - [[View::POS_LOAD]]: enclosed within jQuery(window).load(). Note that by using this position, the method will
     *   automatically register the jQuery js file.
     * - [[View::POS_READY]]: enclosed within jQuery(document).ready(). This is the default value. Note that by using
     *   this position, the method will automatically register the jQuery js file.
     * @param string $key the key that identifies the JS code block. If null, it will use `$js` as the key. If two JS
     * code blocks are registered with the same key, the latter will overwrite the former.
     */
    public function registerWidgetJs($js, $pos = View::POS_READY, $key = null)
    {
        if (empty($js)) {
            return;
        }
        $view = $this->getView();
        WidgetAsset::register($view);
        $view->registerJs($js, $pos, $key);
        $view->registerJs($js, FView::POS_AJAX_COMPLETE, $key); //Hung: need to reload js after Ajax

        if (!empty($this->pjaxContainerId) && ($pos === View::POS_LOAD || $pos === View::POS_READY)) {
            $pjax = 'jQuery("#' . $this->pjaxContainerId . '")';
            $evComplete = 'pjax:complete.' . hash('crc32', $js);
            $script = "setTimeout(function(){ {$js} }, 100);";
            $view->registerJs("{$pjax}.off('{$evComplete}').on('{$evComplete}',function(){ {$script} });");
            // hack fix for browser back and forward buttons
            if ($this->enablePopStateFix) {
                $view->registerJs("window.addEventListener('popstate',function(){window.location.reload();});");
            }
        }
    }
}
