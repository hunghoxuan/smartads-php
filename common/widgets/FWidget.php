<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use iutbay\yii2kcfinder\KCFinderAsset;
use yii\bootstrap\BootstrapWidgetTrait;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


class FWidget extends BaseWidget
{
    use BootstrapWidgetTrait;

    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    public $tag = 'button';
    public $clientOptions = [];

    const TYPE_MODAL = "modal";
    const TYPE_PANEL = "panel";
    const TYPE_NORMAL = "";

    public $display_type = self::TYPE_MODAL;

    const SIZE_LARGE = "modal-lg";
    const SIZE_SMALL = "modal-sm";
    const SIZE_DEFAULT = "";

    /**
     * @var string the header content in the modal window.
     */
    public $header;
    /**
     * @var string additional header options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $headerOptions;
    /**
     * @var string the footer content in the modal window.
     */
    public $footer;
    /**
     * @var string additional footer options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $footerOptions;
    /**
     * @var string the modal size. Can be [[SIZE_LARGE]] or [[SIZE_SMALL]], or empty for default.
     */
    public $size = self::SIZE_LARGE;
    /**
     * @var array|false the options for rendering the close button tag.
     * The close button is displayed in the header of the modal window. Clicking
     * on the button will hide the modal window. If this is false, no close button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to '&times;'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     * Please refer to the [Modal plugin help](http://getbootstrap.com/javascript/#modals)
     * for the supported HTML attributes.
     */
    public $closeButton = [];
    /**
     * @var array the options for rendering the toggle button tag.
     * The toggle button is used to toggle the visibility of the modal window.
     * If this property is false, no toggle button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to 'Show'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     * Please refer to the [Modal plugin help](http://getbootstrap.com/javascript/#modals)
     * for the supported HTML attributes.
     */
    public $toggleButton = false;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if ($this->display_type == self::TYPE_MODAL) {
            $this->initOptions();

            echo $this->renderToggleButton() . "\n";
            echo Html::beginTag('div', $this->options) . "\n";
            echo Html::beginTag('div', ['class' => 'modal-dialog ' . $this->size]) . "\n";
            echo Html::beginTag('div', ['class' => 'modal-content']) . "\n";
            echo $this->renderHeader() . "\n";
            echo $this->renderBodyBegin() . "\n";
        } else if ($this->display_type == self::TYPE_PANEL) {
            $label = ArrayHelper::remove($this->toggleButton, 'label', 'Show');
            echo FHtml::showPanelContent($label, true);
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if ($this->display_type == self::TYPE_MODAL) {
            echo "\n" . $this->renderBodyEnd();
            echo "\n" . $this->renderFooter();
            echo "\n" . Html::endTag('div'); // modal-content
            echo "\n" . Html::endTag('div'); // modal-dialog
            echo "\n" . Html::endTag('div');

            $this->registerPlugin('modal');
        } else if ($this->display_type == self::TYPE_PANEL) {
            echo FHtml::showPanelContent('', false);
        }
    }

    /**
     * Renders the header HTML markup of the modal
     * @return string the rendering result
     */
    protected function renderHeader()
    {
        $button = $this->renderCloseButton();
        if ($button !== null) {
            $this->header = $button . "\n" . $this->header;
        }
        if ($this->header !== null) {
            Html::addCssClass($this->headerOptions, ['widget' => 'modal-header']);
            return Html::tag('div', "\n" . $this->header . "\n", $this->headerOptions);
        } else {
            return null;
        }
    }

    /**
     * Renders the opening tag of the modal body.
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        return Html::beginTag('div', ['class' => 'modal-body']);
    }

    /**
     * Renders the closing tag of the modal body.
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return Html::endTag('div');
    }

    /**
     * Renders the HTML markup for the footer of the modal
     * @return string the rendering result
     */
    protected function renderFooter()
    {
        if ($this->footer !== null) {
            Html::addCssClass($this->footerOptions, ['widget' => 'modal-footer']);
            return Html::tag('div', "\n" . $this->footer . "\n", $this->footerOptions);
        } else {
            return null;
        }
    }

    /**
     * Renders the close button.
     * @return string the rendering result
     */
    protected function renderCloseButton()
    {
        if (($closeButton = $this->closeButton) !== false) {
            $tag = ArrayHelper::remove($closeButton, 'tag', 'button');
            $label = ArrayHelper::remove($closeButton, 'label', '&times;');
            if ($tag === 'button' && !isset($closeButton['type'])) {
                $closeButton['type'] = 'button';
            }

            return Html::tag($tag, $label, $closeButton);
        } else {
            return null;
        }
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => 'fade',
            'role' => 'dialog',
            'tabindex' => -1,
        ], $this->options);
        Html::addCssClass($this->options, ['widget' => 'modal']);

        if ($this->clientOptions !== false) {
            $this->clientOptions = array_merge(['show' => false], $this->clientOptions);
        }

        if ($this->closeButton !== false) {
            $this->closeButton = array_merge([
                'data-dismiss' => 'modal',
                'aria-hidden' => 'true',
                'class' => 'close',
            ], $this->closeButton);
        }

        if ($this->toggleButton !== false) {
            $this->toggleButton = array_merge([
                'data-toggle' => 'modal',
            ], $this->toggleButton);
            if (!isset($this->toggleButton['data-target']) && !isset($this->toggleButton['href'])) {
                $this->toggleButton['data-target'] = '#' . $this->options['id'];
            }
        }
    }
    /**
     * Renders the toggle button.
     * @return string the rendering result
     */
    protected function renderToggleButton()
    {
        if (($toggleButton = $this->toggleButton) !== false) {
            $tag = ArrayHelper::remove($toggleButton, 'tag', $this->tag);
            $toggleButton['href'] = '#';

            $label = ArrayHelper::remove($toggleButton, 'label', 'Show');
            if (!isset($toggleButton['type'])) {
                $toggleButton['type'] = 'button';
            }
            return Html::tag($tag, $label, $toggleButton);
        } else {
            return null;
        }
    }
}