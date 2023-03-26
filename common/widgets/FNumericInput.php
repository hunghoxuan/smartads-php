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
class FNumericInput extends Html5Input
{
    public $type = 'number';
    public $orientation;

    /**
     * @inherit doc
     */
    public function run() {
        $this->options['class'] = 'form-control';

//        if ($this->orientation == 'vertical') {
//            Html::addCssClass($this->containerOptions, 'kv-range-vertical');
//            $this->html5Options['class'] = 'form-control';
//        }
        parent::run();
    }
}
