<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.4
 */

namespace common\widgets\fselect;

use common\components\FHtml;
use common\widgets\BaseWidget;
use kartik\base\Html5Input;
use kartik\base\TranslationTrait;
use kartik\file\FileInput;
use kartik\file\FileInputAsset;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Wrapper for the Bootstrap FileInput JQuery Plugin by Krajee. The FileInput widget is styled for Bootstrap 3.0 with
 * ability to multiple file selection and preview, format button styles and inputs. Runs on all modern browsers
 * supporting HTML5 File Inputs and File Processing API. For browser versions IE9 and below, this widget will
 * gracefully degrade to normal HTML file input.
 *
 * @see http://plugins.krajee.com/bootstrap-fileinput
 * @see https://github.com/kartik-v/bootstrap-fileinput
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 * @see http://twitter.github.com/typeahead.js/examples
 */
class FSelect extends Widget
{
    public $type = 'image-picker';
    public $orientation;
    public $style;
    public $data;
    public $id;
    public $models;
    public $attribute;
    public $model;
    public $name;
    public $hide_select = true;
    public $show_label = true;
    public $item_width = '';
    public $item_height = 'auto';
    public $value;
    public $options = [];
    public $selected_color = '';
    public $multiple;
    public $show_image = true;

    /**
     * @inherit doc
     */
    public function run() {
        if (isset($this->model) && !empty($this->attribute) && !isset($this->value)) {
            $this->value = FHtml::getFieldValue($this->model, $this->attribute);
        }

        if (empty($this->selected_color))
            $this->selected_color = FHtml::settingBackendMainColor();

        if (!$this->show_image)
            $this->show_label = true;


            //$this->display_type = 'jexcel';
        return $this->render($this->type, [
            'id' => $this->id,
            'name' => $this->name,
            'attribute' => $this->attribute,
            'models' => $this->models,
            'data' => $this->data,
            'model' => $this->model,
            'hide_select' => $this->hide_select,
            'show_label' => $this->show_label,
            'item_width' => $this->item_width,
            'item_height' => $this->item_height,
            'value' => $this->value,
            'options' => $this->options,
            'selected_color' => $this->selected_color,
            'multiple' => $this->multiple,
            'show_image' => $this->show_image
        ]);
    }
}
