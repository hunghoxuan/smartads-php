<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use common\widgets\BaseWidget;
use common\widgets\formfield\FieldEdit;
use iutbay\yii2kcfinder\KCFinderAsset;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

class FDetailView extends DetailView
{
    // bootstrap grid column sizes
    const SIZE_LARGE = 'lg';
    const SIZE_MEDIUM = 'md';
    const SIZE_SMALL = 'sm';
    const SIZE_TINY = 'xs';

    // bootstrap maximum grid width
    const GRID_WIDTH = 12;

    /**
     * @var integer, the number of columns in which to split the fields horizontally. If not set, defaults to 1 column.
     */
    public $columns = 1;
    public $labelWidth = 2;
    public $formTag = 'table';
    public $rowTag = 'tr';
    public $fieldTag = 'td';
    public $showPreview = 'top';
    public $preview = true;
    public $form;

    public $print = false;
    public $header = '';
    public $footer = '';
    public $title = '';

    public $field_id = ['id', 'product_id'];
    public $field_name = ['name', 'title', 'username'];
    public $field_image = ['image', 'avatar'];
    public $field_description = ['description', 'overview'];
    public $field_business = [];
    public $field_count = ['count_views', 'count_likes', 'count_purchase'];
    public $field_group = ['category_id', 'type', 'status', 'is_active', 'is_hot', 'is_top', 'is_promotion'];
    public $field_user = ['created_date', 'created_user', 'created_at', 'created_by'];

    /**
     * @var boolean, calculate the number of columns automatically based on count of attributes
     * configured in the Form widget. Columns will be created max upto the Form::GRID_WIDTH.
     */
    public $autoGenerateColumns = false;

    /**
     * @var string, the bootstrap device size for rendering each grid column. Defaults to `SIZE_SMALL`.
     */
    public $columnSize = self::SIZE_SMALL;

    /**
     * @var array the HTML attributes for the grid columns. Applicable only if `$columns` is greater than 1.
     */
    public $columnOptions = [];

    /**
     * @var array the HTML attributes for the rows. Applicable only if `$columns` is greater than 1.
     */
    public $rowOptions = [];

    /**
     * @var array the HTML attributes for the field/attributes container. The following options are additionally
     *     recognized:
     * - `tag`: the HTML tag for the container. Defaults to `fieldset`.
     */
    public $options = ['class' => 'table table-bordered detail-view'];

    /**
     * @var array the default settings that will be applied for all attributes. The array will be
     * configured similar to a single attribute setting value in the `$attributes` array. One will typically
     * default markup and styling like `type`, `container`, `prepend`, `append` etc. The settings
     * at the `$attributes` level will override these default settings.
     */
    public $attributeDefaults = [];

    /**
     * @var string the tag for the fieldset
     */
    private $_tag;

    /**
     * @var string the form orientation
     */
    public $type = ActiveForm::TYPE_HORIZONTAL;

    public $template = '<tr><th class="col-xs-3 col-md-3 form-label">{label}</th><td>{value}</td></tr>';

    public $style_label_horizontal = 'padding:10px';
    public $style_label_vertical = 'padding:8px; color:darkgrey; margin:-7px; margin-bottom: 5px';

    /**
     * Initializes the widget
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->preview)
            $this->showPreview = false;

        if (empty($this->columnSize)) {
            $this->columnSize = empty($this->form->formConfig['deviceSize']) ?
                self::SIZE_SMALL :
                $this->form->formConfig['deviceSize'];
        }
        if (isset($this->form->type)) {
            $this->_orientation = $this->form->type;
        }

        if ($this->autoGenerateColumns) {
            $cols = count($this->attributes);
            $this->columns = $cols >= self::GRID_WIDTH ? self::GRID_WIDTH : $cols;
        }
        echo Html::beginTag($this->_tag, $this->options) . "\n";
    }

    protected function normalizeAttributes()
    {
        //normalize attributes
        $arr = [];
        foreach ($this->attributes as $attribute => $value) {
            if (is_numeric($attribute)) {
                if (is_string($value)) {
                    $arr = array_merge($arr, [$value => ['attribute' => $value]]);
                } else if (is_array($value) && key_exists('attribute', $value)) {
                    $key = $value['attribute'];
                    unset($value['attribute']);
                    $arr = array_merge($arr, [$key => array_merge(['attribute' => $key], $value)]);
                }
            } else {
                if (is_array($value))
                    $arr = array_merge($arr, [$attribute => array_merge($value, ['attribute' => $attribute])]);
                else if (is_string($value))
                    $arr = array_merge($arr, [$attribute => ['attribute' => $attribute, 'value' => $value]]);
                else if (is_callable($value))
                    $arr = array_merge($arr, [$attribute => ['attribute' => $attribute, 'value' => $value]]);
            }
        }
        $this->attributes = $arr;
    }

    public function run() {
        $result = '';

        if (!empty($this->title))
            $result .= FHtml::showGroupHeader($this->title);

        $result .= $this->header;
        $content = $this->renderFieldSet();

        if ($this->showPreview == 'right' || $this->showPreview === true) {
            $preview = FHtml::showModelPreview($this->model, $this->field_name, $this->field_description, $this->field_image, []);

            $result .= "<div class='row'><div class='col-xs-9'> {$content}</div><div class='col-xs-3'> {$preview}</div> </div>";
        } else if ($this->showPreview == 'left') {
            $preview = FHtml::showModelPreview($this->model, $this->field_name, $this->field_description, $this->field_image, []);

            $result .= "<div class='row'><div class='col-xs-3'> {$preview}</div><div class='col-xs-9'> {$content}</div> </div>";
        } else if ($this->showPreview == 'top') {
            $preview = FHtml::showModelPreviewTop($this->model, $this->field_name, $this->field_description, $this->field_image, []);

            $result .= "<div class='row'><div class='col-xs-12'> {$preview}</div><div class='col-xs-12'> {$content}</div> </div>";
        } else if ($this->showPreview == 'bottom') {
            $preview = FHtml::showModelPreviewTop($this->model, $this->field_name, $this->field_description, $this->field_image, []);

            $result .= "<div class='row'><div class='col-xs-12'> {$content} </div><div class='col-xs-12'> {$preview}</div> </div>";
        } else {
            $result .= $content;
        }
        $result .= Html::endTag($this->_tag);
        $result .= $this->footer;
        echo $result;
    }

    protected function renderAttribute($attribute, $index)
    {
        if (FHtml::isInArray($attribute['attribute'], FHtml::FIELDS_HIDDEN))
            return '';

        if (is_string($this->template)) {
            return strtr($this->template, [
                '{label}' => FHtml::getFieldLabel($this->model, $attribute['label']),
                '{value}' => FHtml::showModelField($this->model, $attribute['attribute'], '', FHtml::LAYOUT_NO_LABEL),
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }

    public function run1() {
        $rows = [];
        $i = 0;
        foreach ($this->attributes as $attribute) {
            if (FHtml::isInArray($attribute['attribute'], FHtml::FIELDS_IMAGES)
                || FHtml::isInArray($attribute['attribute'], FHtml::FIELDS_HIDDEN)
                || FHtml::isInArray($attribute['attribute'], FHtml::getFIELDS_GROUP())
            )
                continue;
            $rows[] = $this->renderAttribute($attribute, $i++);
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'table');
        $result = Html::tag($tag, implode("\n", $rows), $options);
    }

    /**
     * Renders the field set
     *
     * @return string
     */
    protected function renderFieldSet()
    {
        $content = '';
        $cols = (is_int($this->columns) && $this->columns >= 1) ? $this->columns : 1;

        $index = 0;
        $attrCount = count($this->attributes);
        $rows = (float)($attrCount / $cols);
        $rows = ceil($rows);

        $names = array_keys($this->attributes);
        $values = array_values($this->attributes);

        $width = (int)(self::GRID_WIDTH / $cols);
        $skip = ($attrCount == 1);

        $form_tag = $this->formTag;
        $row_tag = $this->rowTag;
        $field_tag = $this->fieldTag;

        if ($cols == 1)
            $labelWidth = $this->labelWidth;
        else if ($cols == 2)
            $labelWidth = $this->labelWidth;
        else if ($cols == 3)
            $labelWidth = $this->labelWidth;
        else if ($cols == 4)
            $labelWidth = $this->labelWidth;
        else if ($cols == 12)
            $labelWidth = 0;
        else
            $labelWidth = 0;

        if ($this->type  == ActiveForm::TYPE_VERTICAL)
            $labelWidth = 0;

        //        Html::addCssClass($this->rowOptions, ($attrCount < $cols ? 'pull-left' : 'row') );
        Html::addCssClass($this->rowOptions, ($attrCount < $cols ? 'row' : 'row') );

        $content .= $this->beginTag($form_tag, ['class' =>'col-md-12 col-xs-12 table table-bordered']);

        for ($row = 1; $row <= $rows; $row++) {

            $content .= $this->beginTag($row_tag, $this->rowOptions, $skip);
            $rowCurrentWidth = 0;
            for ($col = 1; $col <= $cols; $col++) {

                if ($index > ($attrCount - 1) || $rowCurrentWidth  >= 12) {
                    break;
                }

                $attribute = $names[$index];
                $settings = $values[$index];
                $settings = array_replace_recursive($this->attributeDefaults, $settings);
                $colOptions = ArrayHelper::getValue($settings, 'columnOptions', $this->columnOptions);

                if (FHtml::isInArray($attribute, FHtml::FIELDS_HIDDEN) || FHtml::isInArray($attribute, FHtml::FIELDS_IMAGES)) {
                    $index++;
                    continue;
                }

                $colWidth = (int)$width - $labelWidth;

                $colSpan = 1;
                if (isset($colOptions['colspan'])) {
                    $colSpan = (int)($colOptions['colspan']);
                }
                $rowCurrentWidth = $rowCurrentWidth + $labelWidth + $colSpan * $colWidth;

                if ($labelWidth > 0) { // has Label
                    $content .= "\t" . $this->beginTag($field_tag, ['class' => "col-$this->columnSize-$labelWidth col-xs-$labelWidth form-label", 'style' => $this->style_label_horizontal], $skip) . "\n";
                    $content .= FHtml::getFieldLabel($this->model, $attribute);
                    $content .= "\t" . $this->endTag($field_tag, $skip) . "\n";
                }

                Html::addCssClass($colOptions, 'col-' . $this->columnSize . '-' . $colWidth);
                $content .= "\t" . $this->beginTag($field_tag, $colOptions, $skip) . "\n";
                if ($labelWidth == 0) {
                    $content .= "<div class='form-label' style='$this->style_label_vertical'>" . FHtml::getFieldLabel($this->model, $attribute) . '</div>';
                }

                if (!empty($settings['attributes'])) {
                    $content .= $this->renderSubAttributes($attribute, $settings, $index);
                } else {
                    if (isset($settings['value'])) {
                        if (is_callable($settings['value']))
                            $content .= "\t\t" . call_user_func($settings['value'], $this->model) . "\n";
                        else
                            $content .= "\t\t" . $settings['value'] . "\n";
                    }
                    else {
                        $content .= "\t\t" . FHtml::showModelFieldValue($this->model, $attribute, 'readonly') . "\n";
                    }
                }
                //$content .= $rowCurrentWidth;
                $content .= "\t" . $this->endTag($field_tag, $skip) . "\n";
                $index++;
            }
            $content .= $this->endTag($row_tag, $skip) . "\n";
        }
        $content .= $this->endTag($form_tag, null);

        return $content;
    }

    /**
     * Begins a tag markup based on orientation
     *
     * @return string
     */
    protected function beginTag($tag, $options, $skip = false)
    {
        if ($this->type !== ActiveForm::TYPE_INLINE && !$skip) {
            return Html::beginTag($tag, $options) . "\n";
        }
        return '';
    }

    /**
     * Ends a tag markup based on orientation
     *
     * @return string
     */
    protected function endTag($tag, $skip = false)
    {
        if ($this->type !== ActiveForm::TYPE_INLINE && !$skip) {
            return Html::endTag($tag) . "\n";
        }
        return '';
    }
}