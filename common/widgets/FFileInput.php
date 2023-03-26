<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.4
 */

namespace common\widgets;

use common\components\FHtml;
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
class FFileInput extends FileInput
{
    use TranslationTrait;

    const TYPE_TEXT = 'input';
    const TYPE_BROWSE = 'browse';

    /**
     * @var bool whether to resize images on client side
     */
    public $resizeImages = false;

    public $display_type = '';

    /**
     * @var bool whether to show 'plugin unsupported' message for IE browser versions 9 & below
     */
    public $showMessage = true;

    /*
     * @var array HTML attributes for the container for the warning
     * message for browsers running IE9 and below.
     */
    public $messageOptions = ['class' => 'alert alert-warning'];

    /**
     * @var array the internalization configuration for this widget
     */
    public $i18n = [];

    /**
     * @inheritdoc
     */
    public $pluginName = 'fileinput';

    /**
     * @var array initialize the FileInput widget
     */
    protected $inputFile;
    protected $inputText;

    public function init()
    {
        parent::init();
        $this->showMessage = false;
        $this->pluginOptions['browseLabel'] = FHtml::t('common', 'Upload');
        $this->pluginOptions['class'] = 'col-md-1 no-padding';

       // var_dump($this->pluginOptions);
       // var_dump($this->options['id']);

        $this->_msgCat = 'fileinput';
        $this->initI18N(__DIR__);
        $this->initLanguage();
        $this->registerAssets();
        if ($this->pluginLoading) {
            Html::addCssClass($this->options, 'btn btn-file pull-right');
        }

        Html::addCssStyle($this->options, 'position: absolute; top: 0; right: 0; margin: 0;padding: 0;font-size: 20px;cursor: pointer;opacity: 0;filter: alpha(opacity=0);');

        $input = $this->getInput('fileInput');
        $script = 'document.getElementById("' . $this->options['id'] . '").className.replace(/\bfile-loading\b/,"");';

        if ($this->showMessage) {
            $validation = ArrayHelper::getValue($this->pluginOptions, 'showPreview', true) ?
                Yii::t('fileinput', 'file preview and multiple file upload') :
                Yii::t('fileinput', 'multiple file upload');
            $message = '<strong>' . Yii::t('fileinput', 'Note:') . '</strong> ' .
                Yii::t(
                    'fileinput',
                    'Your browser does not support {validation}. Try an alternative or more recent browser to access these features.',
                    ['validation' => $validation]
                );
            $content = Html::tag('div', $message, $this->messageOptions) . "<script>{$script};</script>";
            $input .= "\n" . $this->validateIE($content);
        }

        $this->inputFile = $input;
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $this->registerAssetBundle();
        $this->registerPlugin($this->pluginName);
    }

    /**
     * Registers the asset bundle and locale
     */
    public function registerAssetBundle()
    {
        $view = $this->getView();
        if ($this->resizeImages) {
            CanvasBlobAsset::register($view);
            $this->pluginOptions['resizeImage'] = true;
        }
        FileInputAsset::register($view)->addLanguage($this->language, 'fileinput_locale_');
    }

    /**
     * Validates and returns content based on IE browser version validation
     *
     * @param string $content
     * @param string $validation
     *
     * @return string
     */
    protected function validateIE($content, $validation = 'lt IE 10')
    {
        return "<!--[if {$validation}]><br>{$content}<![endif]-->";
    }

    public function run()
    {
        $inputId = $this->options['id'];
        $inputId1 = str_replace('-', '_', $this->options['id']);

        if ($this->display_type == FFileInput::TYPE_BROWSE) {
            $widget = new FileInput(['model' => $this->model, 'name' => $this->name, 'attribute' => $this->attribute]);
            $widget->run();
        } else {
            $accept = key_exists('accept', $this->options) ? $this->options['accept'] : 'All files';
            $max = key_exists('max', $this->options) ? $this->options['max'] : FHtml::settingMaxFileSize();
            if (is_numeric($max))
                $max = FHtml::showNumberInFileSize($max);

            $place_holder = FHtml::t('message', 'Upload files or Paste remote File Url here');

            $file = FHtml::getFieldValue($this->model, $this->attribute);
            $folder = FHtml::getImageFolder($this->model);
            $file_url = FHtml::getFileURL($file, $folder);
            $full_file = FHtml::getFullUploadFolder($folder) . '/' . $file; $file_size = '';
            if (is_file($full_file)) {
                $file_size = FHtml::convertToKBytes(filesize($full_file));
            }
            else
                $file_size = '';

            if (!empty($file_size)) {
                $download = "<a target='_blank' data-pjax=0 title='$file_url' href='$file_url?action=download' class='' > <i class=\"fa fa-download\" aria-hidden=\"true\"></i> " . $file_size . "  </a>";
            }
            else
                $download = '';

            $preview = FHtml::showImageWithDownload($file, $folder, '', '80px', '', '', false, 'download');

            $input = "<input placeholder='$place_holder' type='text' class='row form-control' id='" . $this->options['id'] . "' value='" . $this->value . "' name='" . $this->name . "' />";
            $input = FHtml::showModelFieldToogle($this->value, $input);
            $input = "<div style='width:80%;margin-left: 150px; z-index:1999 !important; margin-top:-63px; float:left' >$input</div>";

            $hint =  "<div class='width:100%'><small style='font-size:80%;color:grey;'>$accept (max: " . $max . ") </small></div>" ;
            $result = $hint;

            if (!empty($preview)) {
                echo '<div class="row1"><div style="padding-left:0px" class="col-xs-8 col-md-10 no-padding">' . $result . '</div><div class="col-xs-12 col-md-12 no-padding" style="padding-top:5px"> ' . $input . '<br/>' .  $preview . $download .  '</div></div>';
            } else {
                echo '<div class="row1"><div style="padding-left:0px" class="col-xs-8 col-md-10 no-padding">' . $result . '</div><div class="col-xs-12 col-md-12 no-padding" style="padding-top:5px">' .  $input . '</div></div>';
            }
        }

        //parent::run(); // TODO: Change the autogenerated stub
    }
}
