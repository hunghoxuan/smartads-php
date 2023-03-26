<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.4
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\base\Html5Input;
use kartik\base\TranslationTrait;
use kartik\file\FileInput;
use kartik\file\FileInputAsset;
use Yii;
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
class FMarkdownEditor extends Html5Input
{
    public $type = 'textarea';
    public $orientation;
    public $style;

    /**
     * @inherit doc
     */
    public function run() {
        $this->options['class'] = 'form-control';
        $id = $this->getId();
        if (key_exists('name', $this->options))
            $this->name = $this->options['name'];

        if (key_exists('value', $this->options))
            $this->value = $this->options['value'];


        echo "<div id='$id-editor'>" . (isset($this->model) ? Html::activeTextarea($this->model, $this->attribute, ['class' => 'form-control']) : Html::textarea($this->name, $this->value, ['class' => 'form-control'])) . "</div>";
        echo FHtml::showToogleMarkdownTextArea($id . '-editor', false);
    }

}
