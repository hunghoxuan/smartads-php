<?php

/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use backend\modules\wp\models\WpPosts;
use backend\modules\wp\Wp;
use common\components\FHtml;
use common\components\FModel;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\jexcel\JExcel;
use kartik\form\ActiveField;
use kartik\widgets\FileInput;
use maksyutin\duallistbox\Widget;
use unclead\multipleinput\MultipleInput;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

class FActiveField extends ActiveField
{
    const RENDER_TYPE_BASIC = 'basic';
    const RENDER_TYPE_ADVANCE = '';

    public $options = ['class' => 'form-group row'];

    public $column;
    public $label_span = 3;
    public $label_text = '';
    public $display_type;
    public $edit_type;
    public $showType;
    public $dbType;
    public $object_type;
    public $appendContent;
    public $prependContent;
    public $labelHint;
    protected $isSettingField = false;
    public $render_type;

    public $params;

    public $toggleButton;
    public $toggleContent;
    public $lookupObject;
    public $populatedFields;

    //public $template = "{label}\n{input}\n{hint}\n{error}";
    protected function buildLayoutParts($showLabels, $showErrors)
    {
        if (!$showErrors) {
            $this->_settings['error'] = '';
        }
        if ($this->skipFormLayout) {
            $this->mergeSettings($showLabels, $showErrors);
            $this->parts['{beginLabel}'] = '';
            $this->parts['{labelTitle}'] = '';
            $this->parts['{endLabel}'] = '';
            return;
        }
        if (!empty($this->_inputCss)) {
            $inputDivClass = $this->_inputCss;
            if ($showLabels === false || $showLabels === ActiveForm::SCREEN_READER) {
                //$inputDivClass = "col-{$this->deviceSize}-{$this->form->fullSpan}"; //==> col-md-10 ???
                $inputDivClass = "row";
            }
            Html::addCssClass($this->wrapperOptions, $inputDivClass);
        }
        if (!isset($this->parts['{beginWrapper}'])) {
            if ($this->renderEmptyWrapper || !empty($this->wrapperOptions)) {
                $options = $this->wrapperOptions;
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
                $this->parts['{endWrapper}'] = Html::endTag($tag);
            } else {
                $this->parts['{beginWrapper}'] = $this->parts['{endWrapper}'] = '';
            }
        }
        $this->mergeSettings($showLabels, $showErrors);
    }

    protected function getRenderType()
    {
        if ($this->form->type !== FActiveForm::TYPE_HORIZONTAL || $this->render_type == self::RENDER_TYPE_BASIC)
            return self::RENDER_TYPE_BASIC;
        return self::RENDER_TYPE_ADVANCE;
    }

    protected function isBasicRenderType()
    {
        if ($this->getRenderType() == self::RENDER_TYPE_BASIC || \Yii::$app->request->isAjax || FHtml::currentAction() == 'index')
            return true;
        return false;
    }

    public function labelSpan($span)
    {
        $this->label_span = $span;
        return $this;
    }

    public function label($label = null, $options = [])
    {
        if ($label === false || $label === null)
            $this->label_span = false;

        if (is_numeric($label)) {
            if ($label == 12) {
                $label = 3;
                $this->display_type = FActiveForm::TYPE_INLINE;
            }
            $this->label_span = $label;
        }

        if (is_numeric($options)) {
            $this->label_span = $options;
            $options = [];
        }

        if (is_string($label))
            $this->label_text = $label;

        return parent::label($label, $options);
    }

    public function displayType($type)
    {
        $this->display_type = $type;
        return $this;
    }

    public function hiddenInput($options = [])
    {
        $this->label_span = -1;
        $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $options);
        $this->parts['{label}'] = '';
        return $this;
    }

    public function getInputId($attribute = '')
    {
        if (empty($attribute))
            $attribute = $this->attribute;

        return Html::getInputId($this->model, $attribute);
    }

    public function getInputName($attribute = '')
    {
        if (empty($attribute))
            $attribute = $this->attribute;

        return Html::getInputName($this->model, $attribute);
    }

    public function getInputContainerId()
    {
        return $this->getInputId() . '-container';
    }

    public function toggleField($toogle_attribute, $isPrimaryField = false, $label = '', $input_container_id = '')
    {
        $attribute_value = FHtml::getFieldValue($this->model, $toogle_attribute);

        $input_id = $this->getInputId();
        $input_id2 = $this->getInputId($toogle_attribute);

        if (empty($input_container_id))
            $input_container_id = $this->getInputContainerId();

        if (empty($label))
            $label = "<div style='padding-top:8px'><i class='fa fa-pencil'></i></div>";

        if ($isPrimaryField || !empty($attribute_value)) {
            $control = $this->parts['{input}'];
            $this->parts['{input}'] = Html::activeTextInput($this->model, $toogle_attribute, ['class' => 'form-control']);
        } else {
            $control = Html::activeTextInput($this->model, $toogle_attribute, ['class' => 'form-control']);
        }

        $this->toggleButton = FHtml::showToogleHtmlControl($input_id, $label, false);
        $this->toggleContent = FHtml::showToogleHtmlControl($input_id, $input_id2, $control, $input_container_id, false);

        return $this;
    }

    public function secondField($toogle_attribute)
    {
        return self::toggleField($toogle_attribute, true, '', '');
    }

    public function toggle($content = '')
    {
        $container_id = $this->getInputContainerId();

        if (!empty($content))
            $this->toggleButton = $content;
        else if (!empty($this->populatedFields) && empty($this->toggleButton)) {
            //if empty $toggleButton then auto Toggle First Attribute in Populated fields
            $lookup_field = array_keys($this->populatedFields)[0];
            if (!empty($lookup_field) && FHtml::field_exists($this->model, $lookup_field))
                $this->toggleField($lookup_field);
        }

        if (!empty($this->toggleButton) && empty($this->toggleContent))
            $this->parts['{input}'] = "<div class='row'><div id='$container_id' class='col-md-11'>" . $this->parts['{input}'] . "</div><div class='col-md-1 no-padding pull-right'>$this->toggleButton</div></div>";
        else if (!empty($this->toggleButton) && !empty($this->toggleContent)) {
            $this->parts['{input}'] = "<div class='row'><div class='col-md-11'><div id='$container_id' >" . $this->parts['{input}'] . '</div>' . $this->toggleContent . "</div><div class='col-md-1 no-padding pull-right'>$this->toggleButton</div></div>";
        } else
            $this->parts['{input}'] = "<div class='row'><div id='$container_id' class='col-md-12'>" . $this->parts['{input}'] . "</div></div>";
        return $this;
    }


    public function render($content = null)
    {
        try {
            return parent::render($content);
        } catch (UnknownPropertyException $ex) {
            FHtml::addError($ex->getMessage());
            return '';
        }
    }

    /**
     * Builds the final template based on the bootstrap form type, display settings for label, error, and hint, and
     * content before and after label, input, error, and hint
     */
    protected function buildTemplate()
    {
        $this->toggle();

        if ($this->form->type == FActiveForm::TYPE_VERTICAL)
            $this->render_type = '';

        $showLabels = $showErrors = $input = $error = null;
        extract($this->_settings);
        if ($this->_isStatic && $this->showErrors !== true) {
            $showErrors = false;
        }
        $showLabels = $showLabels && $this->hasLabels();
        $this->buildLayoutParts($showLabels, $showErrors);
        extract($this->_settings);
        if (!empty($this->_multiselect)) {
            $input = str_replace('{input}', $this->_multiselect, $input);
        }
        if ($this->_isHintSpecial && $this->getHintData('iconBesideInput') && $this->getHintData('showIcon')) {
            $help = str_replace('{help}', $this->getHintIcon(), $this->getHintData('inputTemplate'));
            $input = str_replace('{input}', $help, $input);
        }

        $newInput = $this->contentBeforeInput . $this->generateAddon() . $this->renderFeedbackIcon() . $this->contentAfterInput;
        $newError = "{$this->contentBeforeError}{error}{$this->contentAfterError}";

        //new
        $is_mobile = FHtml::currentDevice()->isMobile();
        $is_mobile_field_css = $is_mobile == true ? 'no-padding' : '';

        if ($this->label_span == -1) //hidden Input
            return $this->parts['{input}'];

        if (FHtml::isDynamicFormEnabled()) {
            $canEdit = true;
            $moduleKey = str_replace('-', '_', FHtml::currentController());
            $modulePath = str_replace('_', '-', $moduleKey);

            if (!isset($this->column)) {
                if (method_exists($this->model, 'getColumn')) {
                    $column = $this->model->getColumn($this->attribute);
                    if (isset($column)) {
                        $this->column = $column;
                    }
                }
            }

            if ($this->isHidden())
                return '';

            if (isset($this->column)) {
                $editor = $this->column->editor;
                $lookup = $this->column->lookup;

                if (self::isReadOnly()) {
                    $showType = '';
                    $this->parts['{input}'] = '<b>' . FHtml::showModelFieldValue($this->model, $this->attribute, $showType) . '</b>';
                } else if (!empty($editor)) {
                    $this->parts['{input}'] = FHtml::buildEditor1($this->model, $this->attribute, $this->form, $editor, $lookup, $this);
                }
                if (!empty($this->column->description))
                    $this->hint($this->column->description);
            }
        }

        if (empty($this->display_type))
            $this->display_type = $this->form->type;



        if ($this->edit_type == FHtml::EDIT_TYPE_INLINE) {

            if ($this->render_type !== FHtml::INPUT_RAW)
                $newInput = FHtml::showModelField($this->model, $this->attribute, $this->showType, FHtml::LAYOUT_NO_LABEL, '');
        } else if ($this->edit_type == FHtml::EDIT_TYPE_VIEW) {
            $newInput = '<div style="padding-top:10px">' . FHtml::showModelFieldValue($this->model, $this->attribute, $this->showType, FHtml::LAYOUT_NO_LABEL, '') . '</div>';
        }

        if (!empty($this->appendContent)) {
            if (strpos($this->appendContent, '{input}') > 0)
                $newInput = str_replace('{input}', $newInput, $this->appendContent);
            else
                $newInput .= $this->appendContent;
        }

        if (!empty($this->prependContent)) {
            if (strpos($this->prependContent, '{input}') > 0)
                $newInput = str_replace('{input}', $newInput, $this->prependContent);
            else
                $newInput .= $this->prependContent . $newInput;
        }

        if (!empty($this->label_text) && is_string($this->label_text))
            $label = FHtml::t('common', $this->label_text);
        else
            $label = FHtml::getFieldLabel($this->model, $this->attribute, $this->isSettingField);

        if ($this->label_span === false) {
            $this->parts['{label}'] = '';
            $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';

            if ($this->form->type == FActiveForm::TYPE_VERTICAL) {
                //$this->parts['{label}'] = "<div class='no-spacing' style='padding-bottom:5px; padding-right: 15px'>&nbsp;" .  '</div>';

                $newInput = "<div class=' $is_mobile_field_css' style='{$style}; padding-right: 15px'><div style='width:100%'>" . $newInput . '</div></div>';
            } else {
                $newInput = "<div class='col-md-12 $is_mobile_field_css' style='{$style};margin-bottom: 10px'><div style='width:100%'>" . $newInput . '</div></div>';
            }
        } else {
            if ($this->form->type == FActiveForm::TYPE_VERTICAL) {
                $this->label_span = 12;
                $this->template = '{label}{input}';
                $this->parts['{label}'] = "<div class='row $is_mobile_field_css' style='margin-bottom: 10px; padding-right: 15px'><div class='col-md-12'><div class='no-spacing' style='padding-bottom:5px; font-weight: bold'>" . $label . $this->labelHint . '</div>';
                $span = 12;
                $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';
                $newInput = "" . $newInput . '</div></div>';
            } else if ($this->form->type == FActiveForm::TYPE_HORIZONTAL) {

                if (!isset($this->label_span)) {
                    $this->label_span = 3;
                }
                $this->parts['{label}'] = "<div class='col-md-12 $is_mobile_field_css' style='margin-bottom: 10px'><div class='row'><div class='col-md-{$this->label_span} col-xs-12 no-spacing form-label' style='padding:10px'>" . $label . $this->labelHint . '</div>';
                $span = $this->form->fullSpan - $this->label_span;
                $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';
                $newInput = "<div class='col-md-{$span} col-xs-12 $is_mobile_field_css' style='{$style}'>" . $newInput . '</div></div></div>';
            }
        }

        if ($this->render_type !== FHtml::INPUT_RAW)
            $newInput = "<div class='hidden-print'>$newInput</div><div class='visible-print'><br/>" . FHtml::showModelField($this->model, $this->attribute, $this->showType, FHtml::LAYOUT_NO_LABEL, '') . "</div>";

        //echo $this->template; echo $newInput; echo $input; echo ': ';

        //echo $this->attribute . ': ' . $newInput . '<br/>';
        $this->template = strtr($this->template, [
            '{label}' => $showLabels ? "{$this->contentBeforeLabel}{label}{$this->contentAfterLabel}" : "",
            '{input}' => str_replace('{input}', $newInput, $input),
            '{error}' => $showErrors ? str_replace('{error}', $newError, $error) : '',
        ]);
        //echo $this->template;

        //$this->template = '{input}';
    }

    public function isHidden()
    {
        if (isset($this->column)) {
            if ($this->column->is_active == false)
                return true;
        }

        return false;
    }

    public function isReadOnly()
    {
        if (isset($this->column)) {
            if ($this->column->is_readonly == 1)
                return true;
        }

        return false;
    }

    //2017/3/21
    public function lookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        $this->isSettingField = true;
        if (is_string($options)) {
            $options = [];
        }
        $id = FHtml::getFieldValue($this->model, $this->attribute);
        $item = FHtml::getModel($object_type, '', $id, null, false);
        $items = [$id => FHtml::getFieldValue($item, $search_field)];
        self::select($items, $options, $object_type, $populated_fields, $search_field, $id_field, $class);
        $result = $this->parts['{input}'];
        $post_id = $id;
        //
        //        if (!empty($post_id)) {
        //            $module = FHtml::getModelModule($object_type);
        //            $controller = str_replace("_", "-", $object_type);
        //            $result .= "<div class='col-md-12>'";
        //            $result .= FHtml::showModalButton(FHtml::t('common', 'Edit'), FHtml::getRootUrl() . "/admin/$module/$controller/index?id=$post_id&action=edit", 'modal-remote', 'btn btn-xs btn-default');
        //            $result .= FHtml::showLinkButton('<span class="glyphicon glyphicon-resize-full"></span>', FHtml::getRootUrl() . "/wordpress/wp-admin/post.php?post=$post_id&action=edit", 'btn btn-xs btn-default');
        //            $result .= "</div>";
        //        }

        $this->parts['{input}'] = $result;
        return $this;
    }

    public function selectCustomRenderer($items, $itemRenderer = null, $options = [])
    {
        $this->isSettingField = true;

        if (!isset($items)) {
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute);
        } else if (is_string($items)) {
            $items = FHtml::getComboArray($items);
        }

        $this->object_type = FHtml::getTableName($this->model);

        if (empty($items)) {
            $this->checkStringFieldValue();
            return self::textInput();
        }

        $this->initDisability($options);
        Html::addCssClass($options, $this->addClass);
        $options = array_merge($this->inputOptions, $options);

        $this->adjustLabelFor($options);
        //$this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $items, $options);
        $name = $this->getInputName();
        $id = $this->getInputId();
        $value = $this->getFieldValue();
        $options['name'] = $name;
        $options['id'] = $id;
        $control = '';
        foreach ($options as $a => $b) {
            $control .= "$a='$b' ";
        }
        $result = ["<select $control>"];
        $result[] = "<option value=''>" . FHtml::getNullValueText() . "</option>";

        foreach ($items as $item) {
            $itemHtml = '';
            if (is_callable($itemRenderer)) {
                $itemHtml = $itemRenderer($item, $value);
            } else {
                if (is_object($item))
                    $id = $item->getPrimaryKey();
                else if (is_array($item)) {
                    $id = $item['id'];
                } else
                    $id = '';

                $selected = $id == $value ? 'selected' : '';
                $itemHtml = "<option value='$id' $selected />";
            }
            $result[] = $itemHtml;
        }
        $result[] = "</select>";
        $this->parts['{input}'] = implode("\n", $result);

        return $this;
    }

    //2017.4.18
    public function select($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        $this->isSettingField = true;

        if (!isset($items)) {
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute);
        } else if (is_string($items)) {
            $items = FHtml::getComboArray($items);
        } else if (is_array($items)) {
            $items = FHtml::getComboArray($items);
        }

        $this->object_type = FHtml::getTableName($this->model);

        if (empty($items)) {
            $this->checkStringFieldValue();
            return self::textInput();
        }

        if (is_bool($options) && $options == true) {
            $this->textInput();
            $this->values($items);
            return $this;
        }

        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);

        if (!empty($lookup_object)) {
            /* @var $class \yii\base\Widget */
            $config['model'] = $this->model;
            $config['attribute'] = $this->attribute;
            $config['view'] = $this->form->getView();
            $config['data'] = $items;
            $config['options'] = $options;
            $config['pluginOptions'] = ['allowClear' => true, 'tags' => true, 'multiple' => false];

            $config = array_merge($config, FHtml::getSelect2Options($this->object_type, $populated_fields, $lookup_object, $search_field, $id_field));
            if (key_exists('pluginOptions', $config))
                $config['pluginOptions'] = array_merge($config['pluginOptions'], ['allowClear' => true, 'tags' => true, 'multiple' => false]);
            $this->parts['{input}'] = $class::widget($config);

            //auto Toggle First Attribute in Populated fields
            $this->lookupObject = $lookup_object;
            $this->populatedFields = $populated_fields;
        } else {
            //$this->buildTemplate();echo $this->label_span . 'fds';

            if (empty($items)) {
                self::textInput();
            } else if (count($items) <= 12 && !$this->isBasicRenderType() && in_array($this->attribute, FHtml::FIELDS_STATUS)) {
                $this->selectHtml($items, $options);
            } else {
                if ($this->isBasicRenderType()) {
                    parent::dropDownList($items);
                    return $this;
                } else {
                    if (count($items) <= 12)
                        $this->selectHtml($items, $options);
                    else
                        parent::select($items, ['style' => 'font-family:Arial, FontAwesome;']);
                }
            }
        }

        return $this;
    }

    public function selectHtml($items, $options = [], $class = 'common\widgets\fselect\FSelect')
    {
        /* @var $class \yii\base\Widget */
        if (is_array($options))
            $config = $options;

        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        $config['data'] = $items;
        $config['options'] = $options;
        $config['value'] = $this->getFieldValue();
        $config['name'] = $this->getInputName();
        $config['id'] = $this->getInputId();

        $this->parts['{input}'] = $class::widget($config);
        return $this;
    }

    public function textInput($options = [])
    {
        $this->checkStringFieldValue();
        return parent::textInput($options);
    }

    public function textbox($options = [])
    {
        return $this->textInput($options);
    }

    public function string($options = [])
    {
        return $this->textInput($options);
    }

    public function selectInput($items = null, $options = [])
    {
        $this->isSettingField = true;

        if (is_string($items)) {
            $items = null;
            $this->object_type = $items;
        }

        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items))
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute, true);

        $this->textInput($options);
        $this->values($items);
        return $this;
    }

    public function selectLookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id')
    {
        return self::select(null, $options, $object_type, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function selectManyLookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id')
    {
        return self::selectMany(null, $options, $object_type, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function selectMany($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        $this->checkArrayFieldValue();

        $this->isSettingField = true;
        $value = $this->getFieldValue();

        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items)) {
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute);
        } else if (is_string($items)) {
            $items = FHtml::getComboArray($items);
        } else if (is_array($items)) {
            $items = FHtml::getComboArray($items);
        }

        //        if (empty($items)) {
        //            $this->checkStringFieldValue();
        //            self::textInput();
        //            return $this;
        //        }
        //        else
        if ((count($items) <= 6  && in_array(str_replace('_array', '', $this->attribute), FHtml::FIELDS_STATUS)) || $this->isBasicRenderType()) {
            $this->selectHtml($items, array_merge($options, ['multiple' => true]));
            //self::checkboxList($items);
            return $this;
        }

        $disabled_items = [];
        foreach ($items as $key => $value) {
            if (StringHelper::endsWith($key, FHtml::NULL_VALUE))
                $disabled_items = array_merge($disabled_items, [$key => ['disabled' => true]]);
        }

        $options['options'] = $disabled_items;
        $config['data'] = $items;
        $config['options'] = $options;

        $config['pluginOptions'] = ['allowClear' => true, 'tags' => true, 'multiple' => true];

        if (!empty($lookup_object)) {
            $config = array_merge($config, FHtml::getSelect2Options($this->object_type, $populated_fields, $lookup_object, $search_field, $id_field));
            if (key_exists('pluginOptions', $config))
                $config['pluginOptions'] = array_merge($config['pluginOptions'], ['allowClear' => true, 'tags' => true, 'multiple' => true]);
        }

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function selectMultiple($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        return self::selectMany($items, $options, $lookup_object, $populated_fields, $search_field, $id_field, $class);
    }

    public function dropDownList($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id')
    {
        return parent::dropDownList($items, $options);
        //return self::select($items, $options, $lookup_object, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function dropdown($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id')
    {
        return parent::dropDownList($items, $options);
        //return self::select($items, $options, $lookup_object, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function dateInput($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = 'common\widgets\FDatePicker')
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);

        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['convertFormat' => true, 'format' => $format, 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => $disabled_date, 'hoursDisabled' => $disabled_hours];

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function dateRange($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = 'common\widgets\FDateRangePicker')
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);

        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['pluginOptions'] = array_merge([
            'ranges' => [],
            'showDropdowns' => true, 'presetDropdown' => false, 'format' => $format, 'class' => 'form-control', 'hideInput' => false, 'timePicker' => true
        ], $options);

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function dateTimeRange($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = 'common\widgets\FDateRangePicker')
    {
        $options = array_merge($options, ['timePicker' => true]);
        return $this->dateRange($format, $options, $disabled_date, $disabled_hours, $class);
    }

    public function datetime($format = 'yyyy-mm-dd hh:ii', $options = [], $disabled_date = '', $disabled_hours = '', $class = FHtml::EDITOR_DATETIME)
    {
        return self::date($format, $options, $disabled_date, $disabled_hours, $class);
    }

    public function date($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = FHtml::EDITOR_DATE)
    {
        $value = $this->getFieldValue();
        if (!empty($value))
            FHtml::setFieldValue($this->model, $this->attribute, date(FHtml::strReplace($format, ['yyyy' => 'Y', 'mm' => 'm', 'dd' => 'd', 'hh' => 'h', 'ii' => 'i', 'ss' => 's']), strtotime($value)));

        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['convertFormat' => true, 'format' => $format, 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => $disabled_date, 'hoursDisabled' => $disabled_hours];

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function selectDate($items = [], $format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = FHtml::EDITOR_DATE)
    {
        if (empty($items))
            $items = [date('Y-m-d', strtotime("-1 days")) => 'Yesterday', date('Y-m-d') => 'Today', date('Y-m-d', strtotime("+1 days")) => 'Tomorrow'];
        if (!empty($items))
            self::values($items);

        return self::date($format, $options, $disabled_date, $disabled_hours, $class);
    }

    public function selectDateInput($items = [], $format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = 'common\widgets\FDatePicker')
    {
        if (empty($items))
            $items = [date('Y-m-d', strtotime("-1 days")) => 'Yesterday', date('Y-m-d') => 'Today', date('Y-m-d', strtotime("+1 days")) => 'Tomorrow'];
        if (!empty($items))
            self::values($items);

        return self::dateInput($format, $options, $disabled_date, $disabled_hours, $class);
    }

    public function fckeditor($options = ['rows' => 5, 'disabled' => false], $preset = 'default', $class = FHtml::EDITOR_HTML)
    {
        return self::html($options, $preset, $class);
    }

    public function textarea($config = [])
    {
        $this->checkStringFieldValue();

        $config['class'] = 'form-control';
        $input_id = $this->getInputId();

        $result = "<div id='$input_id-editor'>" . Html::activeTextarea($this->model, $this->attribute, $config) . "</div>";

        //$result .= FHtml::showModalButton('advanced', FHtml::createUrl('site/editor', ['id' => $input_id]), $role = 'modal-remote', $css = 'btn btn-xs btn-default');
        $result .= '<div style="font-size: 90%; color: lightgrey; padding-top:5px">';
        //$result .= FHtml::t('common', 'Switch editor') . ' : ';
        $result .= FHtml::showToogleHtmlTextArea($input_id, 'html', '');
        $result .= '&nbsp; | &nbsp;';
        $result .= FHtml::showToogleMarkdownTextArea($input_id . '-editor', 'markdown', '');
        $result .= "</div>";
        //$result .= FHtml::showModalButton('markdown', FHtml::createUrl('site/editor', ['id' => $input_id, 'type' => 'markdown']), $role = 'modal-remote', $css = 'btn btn-xs btn-default');

        $this->parts['{input}'] = $result;

        return $this;
    }

    public function markdown($config = [])
    {
        $result = FMarkdownEditor::widget(array_merge(['model' => $this->model, 'attribute' => $this->attribute], $config));
        $this->parts['{input}'] = $result;

        return $this;
    }

    public function html($options = ['rows' => 10], $preset = 'default', $class = FHtml::EDITOR_HTML)
    {
        $value = $this->getFieldValue();
        $options = array_merge($options, ['rows' =>  strlen($value) / 100]);
        return $this->textarea($options);

        //        if ($this->isBasicRenderType()) {
        //            return $this->textarea(['rows' => 5]);
        //        }
        //
        //        $options = array_merge($this->inputOptions, $options);
        //        $this->adjustLabelFor($options);
        //        /* @var $class \yii\base\Widget */
        //        $config['model'] = $this->model;
        //        $config['attribute'] = $this->attribute;
        //        $config['view'] = $this->form->getView();
        //
        //        $config['options'] = $options;
        //
        //        $config['preset'] = $preset;
        //
        //        $this->parts['{input}'] = $class::widget($config);
        //
        //        return $this;
    }

    public function boolean($options = [], $class = FHtml::EDITOR_SWITCH)
    {
        return self::checkbox($options, $class);
    }

    public function checkbox($options = [], $class = FHtml::EDITOR_SWITCH)
    {
        $options = array_merge($this->inputOptions, $options);

        $this->adjustLabelFor($options);

        return parent::checkbox($options, false);

        /* @var $class \yii\base\Widget */
        //        $config['model'] = $this->model;
        //        $config['attribute'] = $this->attribute;
        //        $config['view'] = $this->form->getView();
        //        $options['id'] = $this->getInputId($this->attribute);
        //        $config['options'] = $options;
        //
        //        $this->parts['{input}'] = '<div class="" style="padding-left:15px">' . $class::widget($config, $options) . '</div>';
        //
        //        return $this;
    }


    public function bool($options = [], $class = FHtml::EDITOR_SWITCH)
    {
        return $this->checkbox($options, $class);
    }

    public function image($options = [], $accept = 'image/*', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function video($options = [], $accept = 'video/*', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function audio($options = [], $accept = 'audio/*', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function documents($options = [], $accept = '.docx,.txt,.xls,.pdf,.xlsx,.doc,.ppt,.pptx', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function filesArray($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return $this->filesMultiple($options, $accept, $max, $class);
    }

    public function filesMultiple($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        if ($this->isBasicRenderType()) {
            $file_name = FHtml::getFieldValue($this->model, $this->attribute);
            $folder = FHtml::getUploadFolder($this->model);
            $full_file = FHtml::getFullUploadFolder($folder) . '/' . $file_name;
            $file_size = '';
            if (is_file($full_file)) {
                //$file_size = FHtml::convertToKBytes(filesize($full_file));
                $file = !empty($file_name) ? FHtml::showImage($file_name, $folder, '', '80px') : '';
            } else
                $file = '';

            $options['id'] = $this->getInputId($this->attribute);
            if (empty($file))
                return parent::input('file', $options);
            else
                $this->parts['{input}'] = "<div class='row'><div class='col-md-12'>$file</div>"  . parent::input('file', $options) . "</div>";

            return $this;
        }

        if (empty($accept))
            $accept = FHtml::settingAcceptedFileType();

        if (empty($max))
            $max = FHtml::settingMaxFileSize();

        $options = array_merge($this->inputOptions, $options);
        $options = array_merge(['accept' => $accept, 'multiple' => true], $options);

        $this->adjustLabelFor($options);

        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute . '[]';
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['browseLabel' => FHtml::t('button', 'Upload Files'), 'browseClass' => 'btn btn-primary', 'maxFileSize' => $max, 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any']];
        //var_dump($options); die;
        $this->parts['{input}'] = "<div class='row1'>" .  \kartik\widgets\FileInput::widget($config) . '</div>';
        return $this;
    }

    public function fileInput($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        if ($this->isBasicRenderType()) {
            $file_name = FHtml::getFieldValue($this->model, $this->attribute);
            $folder = FHtml::getUploadFolder($this->model);
            $full_file = FHtml::getFullUploadFolder($folder) . '/' . $file_name;
            $file_size = '';
            if (is_file($full_file)) {
                //$file_size = FHtml::convertToKBytes(filesize($full_file));
                $file = !empty($file_name) ? FHtml::showImage($file_name, $folder, '', '80px') : '';
            } else
                $file = '';

            $options['id'] = $this->getInputId($this->attribute);
            if (empty($file))
                return parent::input('file', $options);
            else
                $this->parts['{input}'] = "<div class='row'><div class='col-md-12'>$file</div>"  . parent::input('file', $options) . "</div>";

            return $this;
        }

        if (empty($accept))
            $accept = FHtml::settingAcceptedFileType();

        if (empty($max))
            $max = FHtml::settingMaxFileSize();

        $options = array_merge($this->inputOptions, $options);
        $options = array_merge(['accept' => $accept, 'multiple' => true], $options);

        $this->adjustLabelFor($options);

        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['maxFileSize' => $max, 'showPreview' => false, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any']];
        //var_dump($options); die;
        $this->parts['{input}'] = "<div class='row1'>" .  $class::widget($config) . '</div>';

        return $this;
    }

    public function file($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function range($options = [], $autoGroup = false, $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => $autoGroup, 'removeMaskOnSubmit' => true]]);

        return self::widget($class, $options);
    }

    public function time($showSeconds = false, $showMeridian = false, $options = [], $class = FHtml::EDITOR_TIME)
    {
        $options = array_merge($options, ['type' => 'time']);
        return self::textInput($options);

        //        $options = array_merge($options, ['pluginOptions' => ['showSeconds' => $showSeconds, 'showMeridian' => $showMeridian]]);
        //
        //        return self::widget($class, $options);
    }

    public function numericInput($options = [], $autoGroup = false,  $class = FHtml::EDITOR_NUMERIC)
    {
        return self::numeric($options, $autoGroup, $class);
    }

    public function numeric($options = [], $autoGroup = false, $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' => 'numeric', 'groupSeparator' => ',', 'autoGroup' => $autoGroup, 'removeMaskOnSubmit' => true]]);

        return self::widget($class, $options);
    }

    public function currencyInput($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function currency($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        $options = array_merge($options, ['clientOptions' => ['prefix' => $prefix . ' ', 'alias' =>  'currency', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]);
        return self::widget($class, $options);
    }

    public function moneyInput($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function money($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function email($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::emailInput($options, $class);
    }

    public function emailInput($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'email']]);
        return self::textInput($options);
    }

    public function url($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::urlInput($options, $class);
    }

    public function urlInput($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'url']]);
        return self::currency($options, $class);
    }

    public function maskedInput($masked = '', $class = FHtml::EDITOR_NUMERIC)
    {
        $options = ['mask' => $masked];
        return self::maskedInput($class, $options);
    }

    public function star($count = 5, $min = 0, $max = 5, $step = 1, $starCaptions = [], $class = FHtml::EDITOR_RATE)
    {
        return self::rate($count, $min, $max, $step, $starCaptions, $class);
    }

    public function rate($count = 5, $min = 0, $max = 5, $step = 1, $starCaptions = [], $class = FHtml::EDITOR_RATE)
    {
        $options = ['pluginOptions' => ['stars' => $count, 'min' => $min, 'max' => $max, 'step' => $step, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => $starCaptions]];
        return self::widget($class, $options);
    }

    public function slide($min = 0, $max = 100, $step = 1, $class = FHtml::EDITOR_SLIDE)
    {
        $options = ['sliderColor' => 'grey', 'handleColor' => 'danger', 'pluginOptions' => ['min' => $min, 'max' => $max, 'step' => 15]];
        return self::widget($class, $options);
    }

    public function progress($min = 0, $max = 100, $step = 1, $class = FHtml::EDITOR_SLIDE)
    {
        $options = ['sliderColor' => 'grey', 'handleColor' => 'danger', 'pluginOptions' => ['min' => $min, 'max' => $max, 'step' => 15]];
        return self::widget($class, $options);
    }

    public function color($class = FHtml::EDITOR_COLOR)
    {
        $options = [];
        $options = ['options' => ['style' => 'border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;']];
        return self::widget($class, $options);
    }

    public function readonly($options = [])
    {
        return self::staticInput($options);
    }

    public function staticInput($options = [])
    {
        $content = isset($this->staticValue) ? $this->staticValue :
            FHtml::showModelFieldValue($this->model, $this->attribute, 'readonly');

        Html::addCssClass($options, 'form-control-static');
        $this->parts['{input}'] = Html::tag('div', $content, $options);
        $this->_isStatic = true;
        return $this;
    }

    public function inline($options = [])
    {
        return self::inlineInput($options);
    }

    public function inlineInput($options = [])
    {
        $content = FHtml::showModelField($this->model, $this->attribute);
        Html::addCssClass($options, 'form-control-static');
        $this->parts['{input}'] = Html::tag('div', $content, $options);
        $this->_isStatic = false;
        return $this;
    }

    //2017.5.17
    public function hintLabel($content, $layout = '<div class="label-hint" style="color:grey; font-size:80%">{content}</div>')
    {
        $this->labelHint =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function appendContent($content, $layout = "<div class='row'><div class='col-md-2'>{input}</div><div class='col-md-10'>{content}</div></div>")
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $content = str_replace(['{id}', '{attribute}'], [$id, $attribute], $content);

        $this->appendContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function appendText($content, $layout = "<div class='row'><div class='col-md-12'>{input}</div><div class=\"col-md-12 label-hint\" style=\"padding-top:5px; color:grey; font-size:80%\">{content}</div></div>")
    {
        return self::appendContent($content, $layout);
    }

    public function hintInput($content, $options = [])
    {
        return self::appendText($content);
    }

    public function description($content, $options = [])
    {
        return self::appendText($content);
    }

    public function help($content, $options = [])
    {
        return self::appendText($content);
    }

    public function prependContent($content, $layout = "<div class='row'><div class='col-md-2'>{content}</div><div class='col-md-10'>{input}</div></div>")
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $content = str_replace(['{id}', '{attribute}'], [$id, $attribute], $content);
        $this->prependContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function renderView($view = '', $params = [], $options = [])
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $this->parts['{input}'] =  FHtml::render($view, '', $params);
        return $this;
    }

    public function appendView($view = '', $params = [], $layout = '<div class="row"><div class="col-md-12">{content}</div></div>')
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $content = FHtml::render($view, '', $params);
        $this->appendContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function prependView($view = '', $params = [], $layout = '<div class="row"><div class="col-md-12">{content}</div></div>')
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $content = FHtml::render($view, '', $params);
        $this->prependContent =  str_replace('{content}', $content, $layout);
        return $this;
    }


    public function values($data = ['' => 'Empty'], $layout = "<div class='row'><div class='col-md-2'>{select}</div><div class='col-md-10'>{input}</div></div>")
    {
        $data = FHtml::getComboArray($data);
        if (strpos($layout, ':') !== false) {
            $arr = explode(':', $layout);
            $idx1 = $arr[0];
            $idx2 = $arr[1];
            $layout = "<div class='row'><div class='col-md-$idx1'>{select}</div><div class='col-md-$idx2'>{input}</div></div>";
        } else if (empty($layout) || $layout == '<br/>') {
            $layout = "{select}<br/>{input}";
        }

        $id = $this->getInputId();
        $attribute = $this->attribute;

        $content = "<select id='{$id}_Select' name='{$id}_Select' class='form-control-small'>";
        $selected = '';
        $is_selected = false;

        $value = FHtml::getFieldValue($this->model, $this->attribute);
        foreach ($data as $key => $value1) {
            $value1 = FHtml::t('common', $value1);
            if (!empty($key) && $key == $value && empty($selected)) {
                $selected = 'selected';
                $is_selected = true;
            } else
                $selected = '';
            $content .=  "<option value='$key' $selected>$value1</option>";
        }

        if (!empty($value) && empty($selected) && !$is_selected)
            $selected = 'selected';
        else
            $selected = '';

        $content .= "<option value='...' $selected>" . " [" . FHtml::t('common', 'Other') . "]" . "</option>";
        $content .= "</select>";

        $content = str_replace(['{id}', '{attribute}'], [$id, $attribute], $content);

        $result = $this->parts['{input}'];
        $this->parts['{input}'] = FHtml::strReplace($layout, ['{select}' => $content, '{input}' => $result]);

        //$this->prependContent =  str_replace('{content}', $content, $layout);

        if (empty($selected))
            FHtml::currentView()->registerJs("$('#$id').hide();");

        FHtml::currentView()->registerJs("$('#{$id}_Select').change(function() {
            var myValue = $(this).val();
            var myText = $('#{$id}_Select :selected').val();
    
            if (myText == 'other' || myText == '...') {
               $('#$id').show();
            }
            else{
                $('#$id').val(myText);
                $('#$id').hide();
            }
        });");

        return $this;
    }

    public function selectCondition($items, $options = [], $condition = [])
    {
        //$items = array_merge([FHtml::NULL_VALUE, $items]);
        $items = FHtml::getComboArray($items);

        $this->select($items, $options);

        if (!empty($condition)) {
            $js = '';
            if (is_string($condition))
                $js = $condition;
            else if (is_array($condition)) {
                $id = $this->getInputId();
                $selected_value = $this->getFieldValue();

                $js = "$('#{$id}').change(function() {
                     var myValue = $(this).val();
                     var myText = $('#$id :selected').val();
                     ";

                foreach ($condition as $key => $value) {
                    $js .= "
                        $('#$value').hide(); 
                        if (myText == '$key') {
                           $('#$value').show();
                        }
                       ";
                }

                $js .= "}); ";

                $i = 0;

                foreach ($condition as $key => $value) {
                    $i =  $i + 1;
                    $js .= "$('#$value').hide(); 
                      ";
                    if (($i == 1 && empty($selected_value)) || $selected_value == $key) {
                        $js .= "$('#$value').show();
                       ";
                    }
                }
            }
            if (!empty($js))
                FHtml::currentView()->registerJs($js);
        }
        return $this;
    }

    public function onChange($value_controls = [], $layout = "<div class='row'><div class='col-md-2'>{content}</div><div class='col-md-10'>{input}</div></div>")
    {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $value = $this->getFieldValue();
        $str = '';

        if (is_array($value_controls)) {
            $total_value = count($value_controls);
            $index = 0;
            foreach ($value_controls as $key => $field_id) {

                if (!StringHelper::startsWith($field_id, '#') && !StringHelper::startsWith($field_id, '.')) { //not class or id (javascript)
                    $field_id = "#$field_id";
                }

                //hide this if current value is not equal to key
                if ($value != $key)
                    FHtml::currentView()->registerJs("$('$field_id').hide();");

                if ($total_value == 1) {
                    $str .= "if (myText == '$key') {
                   $('$field_id').show();
                }
                else{
                    $('$field_id').val(myText);
                    $('$field_id').hide();
                }";
                } else {
                    if ($index == 0) {
                        $str .= "if (myText == '$key') {
                       $('$field_id').show();
                    }";
                    } elseif ($index == $total_value - 1) {
                        $str .= "else if (myText == '$key') {
                       $('$field_id').show();
                    }
                    else{
                        $('$field_id').val(myText);
                        $('$field_id').hide();
                    }";
                    } else {
                        $str .= "else if (myText == '$key') {
                       $('$field_id').show();
                    }";
                    }
                    $index++;
                }
            }

            FHtml::currentView()->registerJs("$('#{$id}').change(function() {
                var myValue = $(this).val();
                var myText = $('#{$id} :selected').val();
                $str;
            });");
        } else if (is_string($value_controls)) {
            FHtml::currentView()->registerJs("$('#{$id}').change(function() {
                $value_controls;
            });");
        }
        return $this;
    }

    public function getFieldValue()
    {
        return FHtml::field_exists($this->model, $this->attribute) ? $this->model->{$this->attribute} : FHtml::getFieldValue($this->model, $this->attribute);
    }

    public function checkStringFieldValue()
    {
        $value = $this->getFieldValue();

        if (is_array($value)) {
            $this->model->{$this->attribute} = FHtml::encode($value);
        }

        return $value;
    }

    public function checkArrayFieldValue()
    {
        $value = $this->getFieldValue();
        if (!is_array($value))
            $this->model->{$this->attribute} = FHtml::decode($value);

        return $value;
    }

    public function checkboxList($items, $options = [], $columns_count = 2)
    {
        $this->checkArrayFieldValue();

        if (is_numeric($options)) {
            $columns_count = $options;
            $options = [];
        }

        $items = FHtml::removeEmptyValues($items);

        if (!key_exists('item', $options)) {
            $column_size = 12 / $columns_count;
            if ($columns_count == 2) {
                $options['item'] = function ($index, $label, $name, $checked, $value) {
                    if (!empty($checked) && $checked > 0)
                        $checked = 'checked';
                    else
                        $checked = '';
                    $arr = explode("/", str_replace('\\...', '', $value));

                    if (\yii\helpers\StringHelper::startsWith($label, 'Module')) {
                        return "<div class='col-md-12 form-label uppercase font-blue-madison' style='margin-bottom:10px;margin-top:20px; font-weight: bold;padding:10px; background-color: #fafafa'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else if (\yii\helpers\StringHelper::endsWith($value, FHtml::NULL_VALUE)) {
                        return "<div class='col-md-12' style='margin-bottom:5px;font-weight: bold;padding:5px;border-bottom: 1px dashed lightgrey'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else {
                        //$label = str_replace(\yii\helpers\BaseInflector::camel2words($arr[0]), '', $label);
                        return "<label class='col-md-6 no-padding checkmark-container' style='font-weight: normal;'><input  style='margin-right:10px' class='$arr[0]' type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}<span class='checkmark'></span></label>";
                    }
                };
            } else if ($columns_count == 3) {
                $options['item'] = function ($index, $label, $name, $checked, $value) {
                    if (!empty($checked) && $checked > 0)
                        $checked = 'checked';
                    else
                        $checked = '';
                    $arr = explode("/", str_replace('\\...', '', $value));

                    if (\yii\helpers\StringHelper::startsWith($label, 'Module')) {
                        return "<div class='col-md-12 form-label uppercase font-blue-madison' style='margin-bottom:10px;margin-top:20px; font-weight: bold;padding:10px; background-color: #fafafa'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else if (\yii\helpers\StringHelper::endsWith($value, FHtml::NULL_VALUE)) {
                        return "<div class='col-md-12' style='margin-bottom:5px;font-weight: bold;padding:5px;border-bottom: 1px dashed lightgrey'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else {
                        //$label = str_replace(\yii\helpers\BaseInflector::camel2words($arr[0]), '', $label);
                        return "<label class='col-md-4 no-padding checkmark-container' style='font-weight: normal;'><input  style='margin-right:10px' class='$arr[0]' type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}<span class='checkmark'></span></label>";
                    }
                };
            } else if ($columns_count == 6) {
                $options['item'] = function ($index, $label, $name, $checked, $value) {
                    if (!empty($checked) && $checked > 0)
                        $checked = 'checked';
                    else
                        $checked = '';
                    $arr = explode("/", str_replace('\\...', '', $value));

                    if (\yii\helpers\StringHelper::startsWith($label, 'Module')) {
                        return "<div class='col-md-12 form-label uppercase font-blue-madison' style='margin-bottom:10px;margin-top:20px; font-weight: bold;padding:10px; background-color: #fafafa'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else if (\yii\helpers\StringHelper::endsWith($value, FHtml::NULL_VALUE)) {
                        return "<div class='col-md-12' style='margin-bottom:5px;font-weight: bold;padding:5px;border-bottom: 1px dashed lightgrey'>{$label}<span class='text-danger glyphicon glyphicon-remove pull-right' onclick='$(\" .{$arr[0]}\").prop(\"checked\", false);'>&nbsp;</span> <span class='text-success glyphicon glyphicon-ok pull-right' onclick='$(\".{$arr[0]}\").prop(\"checked\", true);'>&nbsp;</span>  </div>";
                    } else {
                        //$label = str_replace(\yii\helpers\BaseInflector::camel2words($arr[0]), '', $label);
                        return "<label class='col-md-2 no-padding checkmark-container' style='font-weight: normal;'><input  style='margin-right:10px' class='$arr[0]' type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}<span class='checkmark'></span></label>";
                    }
                };
            }
        }

        return parent::checkboxList($items, $options);
    }

    public function listBox($items = null, $options = [])
    {
        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items))
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute, true);

        if (empty($items))
            return $this->textInput();
        else if (count($items) <= 5)
            return self::radioButtonGroup($items, $options);
        else
            return parent::listBox($items, $options);
    }

    public function radioList($items = null, $options = [])
    {
        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items))
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute, true);

        if (empty($items))
            return $this->textInput();
        else if (count($items) <= 5)
            return self::radioButtonGroup($items, $options);
        else
            return parent::radioList($items, $options);
    }

    public function selectRadio($items = null, $options = [])
    {
        return self::radioList($items, $options);
    }

    public function radioButtonGroup($items, $options = [])
    {
        $items1 = $items; //FHtml::removeEmptyValues($items);
        //$options = array_merge(['style' => 'padding-left:20px']);
        if (count($items1) > 1) {

            return parent::radioList($items1, $options);
        } else
            return parent::dropDownList($items1, $options);
    }

    public function map($address_field = 'address', $lat_field = 'lat', $long_field = 'long', $api_key = '', $class = '\pigolab\locationpicker\CoordinatesPicker')
    {

        if (empty($api_key))
            $api_key = FHtml::setting('google_map_api_key', 'AIzaSyDt8PQG_AqjJnmYJLp5XXXHLweFfS8YWc0');

        if (empty($this->object_type))
            $this->object_type = FHtml::getTableName($this->model);

        $options = [
            'key' => "$api_key",   // optional , Your can also put your google map api key
            'valueTemplate' => '{latitude},{longitude}', // Optional , this is default result format
            'options' => [
                'style' => 'height: 400px',  // map canvas width and height
            ],
            'enableSearchBox' => true, // Optional , default is true
            'searchBoxOptions' => [ // searchBox html attributes
                'style' => 'width: 90%;', // Optional , default width and height defined in css coordinates-picker.css
            ],
            'enableMapTypeControl' => false, // Optional , default is true, deprecated
            //yii2-jquery-locationpicker : enableMapTypeControl is deprecated since 0.2.0 , we recommand use mapOptions to define google map options.
            'clientOptions' => [
                //add location default
                'location' => [
                    'latitude' => $this->model->isNewRecord ? null : FHtml::getFieldValue($this->model, $lat_field),
                    'longitude' => $this->model->isNewRecord ? null : FHtml::getFieldValue($this->model, $long_field)
                ],
                //change radius to 0
                'radius' => 300,
                'addressFormat' => 'street_number',
                //passing data to lattitude and longitude field
                'inputBinding' => [
                    'latitudeInput' => new JsExpression("$('#$this->object_type-$lat_field')"),
                    'longitudeInput' => new JsExpression("$('#$this->object_type-$long_field')"),
                ]
            ]
        ];
        self::widget($class, $options);
        FHtml::currentView()->registerJs(@"
        $(document).ready(function() {
            $('#$this->object_type-$this->attribute-searchbox').change(function(){
                var content = $('#$this->object_type-$this->attribute-searchbox').val();
                $('#$this->object_type-$address_field').val(content);
            });
            $('#$this->object_type-$address_field').change(function(){
                var content = $('#$this->object_type-$address_field').val();
                $('#$this->object_type-$this->attribute-searchbox').val(content);
                jQuery('#$this->object_type-$this->attribute-searchbox').focus();
                jQuery('#$this->object_type-$this->attribute-searchbox').keypress();
                //jQuery('#$this->object_type-$this->attribute-searchbox').locationpicker('map').map.controls[google.maps.ControlPosition.TOP_LEFT].push(jQuery('#$this->object_type-$this->attribute-searchbox').get(0));
            });
        });", \yii\web\View::POS_END);
        return $this;
    }

    public function dynamicInput($editor = '', $lookup = '', $options = [])
    {
        //$this->parts['{input}'] = FHtml::buildEditor($this->model, $this->attribute, $this->form, $editor, $lookup, $this);
        FHtml::buildEditor($this->model, $this->attribute, $this->form, $editor, $lookup, $this);
        return $this;
    }

    public function editor($editor = '', $lookup = '', $options = [])
    {
        return self::dynamicInput($editor, $lookup, $options);
    }

    public function multipleInput($columns = [], $min = 0, $max = null, $position =  MultipleInput::POS_ROW)
    {
        $value = FHtml::getFieldValue($this->model, $this->attribute);
        if (!is_array($value)) {
            $value = FHtml::decode($value);
        }

        if (!is_array($value))
            $value = [];

        $table = FHtml::getTableName($this->model);

        if (empty($value) && empty($table)) {
            $key = $this->attribute;
            $pos = strrpos($key, '_');
            if ($pos > 0)
                $key = substr_replace($key, '.', $pos, 1);
            $value = FHtml::getKeyValueArray($key);
        }

        FHtml::setFieldValue($this->model, $this->attribute, $value);

        if ($max === true)
            $max = null;

        if ($min == $max)
            $position = null;

        if (is_array($columns) && key_exists('columns', $columns))
            $options = $columns;
        else if (is_array($columns)) {
            $columns1 = [];
            foreach ($columns as $key => $value) {
                if (is_array($value)) {
                    if (!key_exists('type', $value)) {
                        $columns1[] = [
                            'name' => $key,
                            'enableError' => true,
                            'type' => 'dropDownList',
                            'title' => !empty($label) ? FHtml::t('common', $label) : ((is_string($key) || is_numeric($key)) ? FHtml::t('common', $key) : (is_string($key) ? FHtml::t('common', $key) : false)),
                            'options' => [
                                'class' => 'col-md-3 form-label',
                                'style' => 'border:solid 1px lightgray',

                            ],
                            'items' => FHtml::getComboArray($value),
                            'headerOptions' => [
                                'style' => 'vertical-align:middle'
                            ]
                        ];
                    } else {
                        if (!key_exists('name', $value))
                            $value['name'] = $key;
                        $columns1[] = $value;
                    }
                } else if (is_string($value)) {
                    if (is_numeric($key) || strpos($value, ':') !== false) {
                        $name = is_string($key) ? $key : $value;
                        $name_arr = FModel::parseAttribute($name);
                        $name = $name_arr['attribute'];
                        $label = isset($name_arr['label']) ? $name_arr['label'] : $name;
                        $format = $name_arr['format'];
                        $type = $name_arr['editor'];
                        $items = $name_arr['items'];

                        if (empty($type))
                            $type = 'textInput';

                        $columns1[] = [
                            'name' => $name,
                            'enableError' => true,
                            'type' => $type,
                            'title' => !empty($label) ? FHtml::t('common', $label) : ((is_string($key) || is_numeric($key)) ? FHtml::t('common', $name) : (is_string($key) ? FHtml::t('common', $key) : false)),
                            'options' => in_array($name, ['id', 'key', 'meta_key']) ? [
                                'class' => 'col-md-3 form-label',
                                'style' => 'border:solid 1px lightgray',

                            ] : false,
                            'items' => $items,
                            'headerOptions' => [
                                'class' => in_array($name, ['id', 'key', 'meta_key']) ? 'col-md-3' : '',
                                'style' => 'vertical-align:middle'
                            ]
                        ];
                    } else if (is_string($key)) {
                        $columns1[] = [
                            'name' => $key,
                            'enableError' => true,
                            'type' => $value,
                            'title' => !empty($label) ? FHtml::t('common', $label) : ((is_string($key) || is_numeric($key)) ? FHtml::t('common', $key) : (is_string($key) ? FHtml::t('common', $key) : false)),
                            'options' => [
                                'class' => 'col-md-3 form-label',
                                'style' => 'border:solid 1px lightgray',

                            ],
                            //'items' => $value,
                            'headerOptions' => [
                                'style' => 'vertical-align:middle'
                            ]
                        ];
                    }
                }
            }

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => $position, // show add button in the header,
                'columns' => $columns1
            ];
        } else if (is_string($columns)) {

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => $position, // show add button in the header,
                'columns' => [[
                    'name' => $columns,
                    'enableError' => true,
                    'title' => false
                ]]
            ];
        } else {

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => $position, // show add button in the header,
                'columns' => []
            ];
        }

        $this->widget(MultipleInput::className(), $options);

        return $this;
    }

    public function fieldsInput($fields = [])
    {
        $value = FHtml::getFieldValue($this->model, $this->attribute);
        if (!is_array($value)) {
            $value = FHtml::decode($value);
        }

        if (is_array($value))
            $value1 = ArrayHelper::map($value, 'Field', 'Value');
        else {
            $value = [];
            $value1 = [];
        }

        $this->params = $fields;

        foreach ($fields as $field => $field_input) {
            if (is_numeric($field)) {
                $field = $field_input;
                $field_input = 'text';
            }

            $name_arr = FModel::parseAttribute($field);
            $name = $name_arr['attribute'];

            if (!key_exists($name, $value1))
                $value[] = ['Field' => $name, 'Value' => ''];
        }

        FHtml::setFieldValue($this->model, $this->attribute, $value);

        $columns = $fields;

        $position = false;
        $max = count($fields);
        $min = count($fields);

        if (is_array($columns) && key_exists('columns', $columns))
            $options = $columns;
        else if (is_array($columns)) {
            $columns1 = [];

            $columns1[] = [
                'name' => 'Field',
                //'enableError' => true,
                'title' => FHtml::t('common', 'Attribute'),
                'options' => [
                    'class' => 'col-md-3',
                    'style' => 'border:solid 1px lightgrey;',
                    'readonly' => true
                ],
                'headerOptions' => [
                    'class' => 'form-label col-md-3', 'style' => ''
                ]
            ];
            $columns1[] = [
                'name' => 'Value',
                'type' => FDynamicInput::className(),
                'title' => FHtml::t('common', 'Value'),
                'headerOptions' => [
                    'class' => 'col-md-9', 'style' => ''
                ],
                'options' => function ($data) {
                    if (empty($data))
                        $data = [];

                    $field_name = key_exists('Field', $data) ? $data['Field'] : '';
                    $field_value = key_exists('Value', $data) ? $data['Value'] : null;
                    $fields = $this->params;
                    $type = 'textInput';
                    $index = 0;
                    $items = [];

                    foreach ($fields as $field => $field_input) {

                        if (is_numeric($field)) {
                            $field = $field_input;
                            $field_input = 'text';
                        }

                        $name_arr = FModel::parseAttribute($field);
                        $name = $name_arr['attribute'];
                        $type = !empty($name_arr['format']) ? $name_arr['format'] : $field_input;
                        $items = $name_arr['items'];

                        if ($field_name == $name)
                            break;

                        $index += 1;
                    }

                    if (empty($type))
                        $type = 'textInput';

                    //echo "$field_name: $index: $type : $field_value ---";
                    return ['form' => $this->form, 'model' => $this->model, 'type' => $type, 'items' => $items];
                }
            ];

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => $position, // show add button in the header,
                'columns' => $columns1
            ];
        } else if (is_string($columns)) {

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => $position, // show add button in the header,
                'columns' => [[
                    'name' => $columns,
                    'enableError' => true,
                    'title' => false
                ]]
            ];
        } else {

            $options = [
                'min' => $min, 'max' => $max,
                'addButtonPosition' => null, // show add button in the header,
                'columns' => []
            ];
        }

        $this->widget(MultipleInput::className(), $options);

        return $this;
    }

    public function multipleFiles($view_path = '', $options = [])
    {
        $this->render_type = FHtml::INPUT_RAW;

        $options = array_merge(['model' => $this->model, 'form' => $this->form, 'canEdit' => !$this->isReadOnly()], $options);
        if (!empty($view_path))
            $options = array_merge($options, ['view_path' => $view_path]);

        $this->parts['{input}'] = FormObjectFile::widget($options);
        return $this;
    }

    public function files($view_path = '', $options = [])
    {
        return $this->multipleFiles($view_path, $options);
    }

    public function attributes($options = [])
    {
        $options = array_merge(['model' => $this->model, 'form' => $this->form, 'canEdit' => !$this->isReadOnly()], $options);

        $this->parts['{input}'] = FormObjectAttributes::widget($options);
        return $this;
    }

    public function arrayInput($columns = [], $min = 0, $max = null, $position =  MultipleInput::POS_ROW)
    {
        return $this->multipleInput($columns, $min,  $max, $position);
    }

    public function arrayKeyValuesInput($columns = ['id', 'name'])
    {
        $table = FHtml::getTableName($this->model);
        $value = FHtml::getFieldValue($this->model, $this->attribute);

        if (empty($value) && empty($table)) {
            $key = $this->attribute;
            $pos = strrpos($key, '_');
            if ($pos > 0)
                $key = substr_replace($key, '.', $pos, 1);
            $value = FHtml::getKeyValueArray($key);
        }

        FHtml::setFieldValue($this->model, $this->attribute, $value);
        return $this->arrayInput($columns);
    }

    public function tagsInput()
    {
        return $this->multipleInput([]);
    }

    public function tags()
    {
        return $this->multipleInput(['label', 'url']);
    }

    public function links()
    {
        return $this->multipleInput(['label', 'url']);
    }

    public function text($simple = true)
    {
        if ($simple)
            return $this->textarea();
        return $this->html();
    }

    public function emailsArray($max = null)
    {
        return $this->multipleInput(['name', 'email'], 1, $max);
    }

    public function coordinate($max = 1)
    {
        return $this->multipleInput(['latitude', 'longtitude'], 1, $max);
    }

    public function coordinateArray($max = null)
    {
        return $this->coordinate($max);
    }

    public function address($max = 1)
    {
        return $this->multipleInput(['address', 'city', 'post', 'lat', 'long'], 1, $max);
    }

    public function addressArray($max = null)
    {
        return $this->multipleInput(['address', 'city', 'post', 'lat', 'long'], 1, $max);
    }

    public function relation($table, $options = [], $object_fields = [], $relation_type = '',  $grid_type = 'list', $view_path = '')
    {
        $this->render_type = FHtml::INPUT_RAW;

        $options = array_merge(['model' => $this->model, 'form' => $this->form, 'canEdit' => !$this->isReadOnly()], $options);
        if (!empty($view_path))
            $options = array_merge($options, ['view_path' => $view_path]);

        $options = array_merge($options, ['relation_type' => $relation_type]);

        $options = array_merge($options, ['object_type' => $table]);
        $options = array_merge($options, ['object_fields' => $object_fields]);
        //$options = array_merge($options, ['field_name' => $this->attribute]);
        $options = array_merge($options, ['_attribute' => $this->attribute]);
        $options = array_merge($options, ['grid_type' => $grid_type]);
        $options = array_merge($options, ['title' => '']);

        //$options = array_merge($options, ['default_fields' => ['object_id' => $this->model->getPrimaryKey(), 'object_type' => $this->object_type]]);

        $this->parts['{input}'] = FormRelations::widget($options);
        return $this;
    }

    public function oneToMany($table, $options = [], $object_fields = [], $relation_type = '', $grid_type = '', $view_path = '')
    {
        return self::relation($table, $options,  $object_fields, $relation_type, $grid_type, $view_path);
    }

    public function hasOne($table, $options = [], $object_fields = [], $relation_type = '', $grid_type = '', $view_path = '')
    {
        return self::oneToMany($table, $options, $object_fields, $relation_type, $grid_type, $view_path);
    }

    public function manyToMany($table, $options = [], $object_fields = [], $relation_type = '', $grid_type = '', $view_path = '')
    {
        return self::relation($table, $options, $object_fields, $relation_type, $grid_type, $view_path);
    }

    public function hasMany($table, $options = [], $object_fields = [], $relation_type = '', $grid_type = '', $view_path = '')
    {
        return self::manyToMany($table, $options, $object_fields, $relation_type, $grid_type, $view_path);
    }

    public function wordpress($condition = [], $field = 'wp_post_id', $options = [])
    {
        $condition = is_array($condition) ? array_merge(['post_status' => Wp::POST_STATUS_PUBLISH, 'post_type' => Wp::POST_TYPE_POST], $condition) : $condition;
        $models = WpPosts::findComboArray($condition, 'ID', '{post_title} ({post_type}: {ID})');

        $this->select($models, $options);

        $result = $this->parts['{input}'];

        $post_id = FHtml::getFieldValue($this->model, $field);

        if (!empty($post_id)) {
            $result .= FHtml::showModalButton(FHtml::t('common', 'Edit'), FHtml::getRootUrl() . "/wordpress/wp-admin/post.php?post=$post_id&action=edit", 'iframe', 'btn btn-xs btn-default');
            $result .= FHtml::showLinkButton('<span class="glyphicon glyphicon-resize-full"></span>', FHtml::getRootUrl() . "/wordpress/wp-admin/post.php?post=$post_id&action=edit", 'btn btn-xs btn-default');
        } else {
        }

        $result .= '<div class="pull-right">';
        $result .= FHtml::showModalButton(FHtml::t('common', 'Create'), FHtml::getRootUrl() . "/wordpress/wp-admin/post-new.php", 'iframe', 'btn btn-xs btn-success');
        $result .= FHtml::showLinkButton('<span class="glyphicon glyphicon-resize-full"></span>', FHtml::getRootUrl() . "/wordpress/wp-admin/post-new.php", 'btn btn-xs btn-success');
        $result .= '</div>';


        $this->parts['{input}'] = $result;
        return $this;
    }

    public function jexcel($columns = ['Name', 'Overview'])
    {
        $data = $this->getFieldValue();
        $result = JExcel::widget(['data' => $data, 'id' => $this->getInputId(), 'colHeaders' => $columns, 'attribute' => $this->attribute, 'model' => $this->model, 'name' => $this->getInputName()]);
        $this->parts['{input}'] = $result;
        return $result;
    }
}
