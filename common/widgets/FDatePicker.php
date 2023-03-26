<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\date\DatePicker;
use yii\helpers\BaseInflector;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FDatePicker extends DatePicker
{
    public $format = 'yyyy-mm-dd';
    public $disabled_date = '';
    public $disabled_hours = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $id = strtolower(str_replace('_', '', FHtml::getTableName($this->model))) . '-'. $this->attribute;
        $name = BaseInflector::camelize(FHtml::getTableName($this->model)) . '[' . $this->attribute . ']';
        $name1 = BaseInflector::camelize(FHtml::getTableName($this->model)) . '[_' . $this->attribute . '_hidden]';

        $this->options = array_merge($this->options, ['id' => '_' . $id . '_hidden', 'name' => $name1]);
        $this->type = DatePicker::TYPE_BUTTON;
        $value = FHtml::getFieldValue($this->model, $this->attribute);

        $this->pluginOptions = ['convertFormat' => true, 'format' => $this->format, 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => $this->disabled_date, 'hoursDisabled' => $this->disabled_hours ];
        $this->pluginEvents = ['changeDate' => "function() { $('#$id').val($('#_{$id}_hidden').val());}"];

        echo "<div class='row'><div class='col-md-10' style='padding-right:0px !important; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;'><input type='text' class='form-control' id='$id' name='$name' value='$value' /></div><div class='row'>";//pull-left -> row
        parent::run();
        echo '</div></div>';
    }
}
