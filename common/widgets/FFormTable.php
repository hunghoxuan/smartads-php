<?php
/**
 * @package   yii2-builder
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @version   1.6.0
 */

namespace common\widgets;

use common\components\FContent;
use common\components\FHtml;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Inflector;
use yii\widgets\ActiveField;

/**
 * A form builder widget for rendering the form attributes using kartik\form\ActiveForm.
 * The widget uses Bootstrap 3.x styling for generating form styles and multiple field columns.
 * Usage:
 * ```
 *   use kartik\form\ActiveForm;
 *   use kartik\builder\Form;
 *   $form = ActiveForm::begin($options); // $options is array for your form config
 *   echo Form::widget([
 *       'model' => $model, // your model
 *       'form' => $form,
 *       'columns' => 2,
 *       'attributes' => [
 *           'username' => ['type' => Form::INPUT_TEXT, 'options'=> ['placeholder'=>'Enter username...']],
 *           'password' => ['type' => Form::INPUT_PASSWORD],
 *           'rememberMe' => ['type' => Form::INPUT_CHECKBOX, 'enclosedByLabel' => true],
 *       ]
 *   ]);
 *   ActiveForm::end();
 * ```
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class FFormTable extends Widget
{
	// bootstrap grid column sizes
	const SIZE_LARGE  = 'lg';
	const SIZE_MEDIUM = 'md';
	const SIZE_SMALL  = 'sm';
	const SIZE_TINY   = 'xs';


	// form inputs
	const INPUT_HIDDEN        = 'hiddenInput';
	const INPUT_TEXT          = 'textInput';
	const INPUT_TEXTAREA      = 'textarea';
	const INPUT_PASSWORD      = 'passwordInput';
	const INPUT_DROPDOWN_LIST = 'dropdownList';
	const INPUT_LIST_BOX      = 'listBox';
	const INPUT_CHECKBOX      = 'checkbox';
	const INPUT_RADIO         = 'radio';
	const INPUT_CHECKBOX_LIST = 'checkboxList';
	const INPUT_RADIO_LIST    = 'radioList';
	const INPUT_MULTISELECT   = 'multiselect';
	const INPUT_STATIC        = 'staticInput';
	const INPUT_FILE          = 'fileInput';
	const INPUT_HTML5         = 'input';
	const INPUT_WIDGET        = 'widget';
	const INPUT_HTML          = 'html';
	const INPUT_NUMERIC       = 'numeric';
	const INPUT_READONLY      = 'readonly';
	const INPUT_INLINE        = 'inline';

	const INPUT_RAW = 'raw'; // any free text or html markup

	// bootstrap maximum grid width
	const GRID_WIDTH = 12;

	/**
	 * @var yii\db\ActiveRecord | yii\base\Model the model used for the form
	 */
	public $model;

	public $print    = false;
	public $header   = '';
	public $footer   = '';
	public $title    = '';
	public $overview = '';
	public $visible  = true;

	public $readonly = false;
	public $edit_type;
	public $display_type;
	public $type;

	public $hide_field = false;

    /**
     * field show hide fieldset.
     * default is false
     * @var bool
     */
    public $open = true;

	/**
	 * @var integer, the number of columns in which to split the fields horizontally. If not set, defaults to 1 column.
	 */
	public $columns    = 1;
	public $labelWidth = 2;
	public $formTag;
	public $rowTag;
	public $fieldTag;
	public $formCSS;

	/**
	 * @var ActiveForm the form instance
	 */
	public $form;

	/**
	 * @var string the form name to be provided if not using with model
	 * and ActiveForm
	 */
	public $formName;

	/**
	 * @var array the attribute settings. This is an associative array, which needs to be setup as
	 * `$attribute_name => $attribute_settings`, where:
	 * - `attribute_name`: string, the name of the attribute
	 * - `attribute_settings`: array, the settings for the attribute, where you can set the following:
	 *    - 'type': string, the input type for the attribute. Should be one of the INPUT_ constants.
	 *       Defaults to `INPUT_TEXT`.
	 *    - 'attributes': array, the nested group of sub attributes that will be grouped together, this
	 *      configuration will be similar to attributes. The label property will be auto set to `false`
	 *      for each sub attribute.
	 *    - 'value': string|Closure, the value to be displayed if the `type` is set to `INPUT_RAW`. This will display
	 *       the raw text from value field if it is a string. If this is a Closure, your anonymous function call should
	 *       be of the type: `function ($model, $key, $index, $widget) { }, where $model is the current model, $key is
	 *       the key associated with the data model $index is the zero based index of the dataProvider, and $widget
	 *       is the current widget instance.`
	 *    - 'format': string|array, applicable only for INPUT_STATIC type (and only in tabular forms). This
	 *      controls which format should the value of each data model be displayed as (e.g. `"raw"`, `"text"`,
	 *      `"html"`, `['date', 'php:Y-m-d']`). Supported formats are determined by [Yii::$app->formatter].
	 *      Default format is "raw".
	 *    - 'label': string, (optional) the custom attribute label. If this is not set, the model attribute label
	 *      will be automatically used. If you set it to false, the `label` will be entirely hidden.
	 *    - 'labelSpan': int, the grid span width of the label container, which is especially useful for horizontal forms.
	 *      If not set this will be derived automatically from the `formConfig['labelSpan']` property of `$form` (ActiveForm).
	 *    - 'labelOptions': array, (optional) the HTML attributes for the label. Will be applied only when NOT using
	 *      with active form and only if label is set.
	 *    - 'prepend': string, (optional) any markup to prepend before the input. For ActiveForm fields, this content
	 *      will be prepended before the field group (including label, input, error, hint blocks).
	 *    - 'append': string, (optional) any markup to append before the input. For ActiveForm fields, this content
	 *      will be appended after the field group (including label, input, error, hint blocks).
	 *    - 'container': array, (optional) HTML attributes for the `div` container to wrap the input. For ActiveForm,
	 *      this will envelop the field group (including label, input, error, hint blocks). If not set or empty, no
	 *      container will be wrapped.
	 *    - 'inputContainer': array, (optional) HTML attributes for the `div` container to wrap the
	 *      input control only. If not set or empty, no container will be wrapped. Will be applied
	 *      only when NOT using with ActiveForm.
	 *    - 'fieldConfig': array, the configuration for the active field.
	 *    - `hint`: string, the hint text to be shown below the active field.
	 *    - 'items': array, the list of items if input type is one of the following:
	 *      `INPUT_DROPDOWN_LIST`, `INPUT_LIST_BOX`, `INPUT_CHECKBOX_LIST`, `INPUT_RADIO_LIST`, `INPUT_MULTISELECT`
	 *    - `enclosedByLabel`: bool, if the `INPUT_CHECKBOX` or `INPUT_RADIO` is to be enclosed by label. Defaults
	 *      to `true`.
	 *    - html5type: string, the type of HTML5 input, if input type is set to `INPUT_HTML5`.
	 *    - 'widgetClass': string, the classname if input type is `INPUT_WIDGET`.
	 *    - 'options': array, the HTML attributes or widget settings to be applied to the input.
	 *    - 'columnOptions': array, for a `Form`, it will override columnOptions setup at `Form` level. For
	 *      a `TabularForm` it will allow you to append additional column options for the grid data column.
	 */
	public $attributes = [];

	/**
	 * @var array the default settings that will be applied for all attributes. The array will be
	 * configured similar to a single attribute setting value in the `$attributes` array. One will typically
	 * default markup and styling like `type`, `container`, `prepend`, `append` etc. The settings
	 * at the `$attributes` level will override these default settings.
	 */
	public $attributeDefaults = [];

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
	public $options = [];

	/**
	 * @var string the tag for the fieldset
	 */
	private $_tag;

	/**
	 * @var string the form orientation
	 */
	private $_orientation = ActiveForm::TYPE_VERTICAL;

	/**
	 * Initializes the widget
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init() {

		if (empty($this->attributes)) {
			return;
		}



		$this->checkBaseConfig();
		parent::init();
		$this->normalizeAttributes();

		$this->checkFormConfig();
		if (empty($this->columnSize)) {
			$this->columnSize = empty($this->form->formConfig['deviceSize']) ? self::SIZE_SMALL : $this->form->formConfig['deviceSize'];
		}

		if (isset($this->form->type)) {
			$this->_orientation = $this->form->type;
		}

		$this->initOptions();
		$this->registerAssets();
		if ($this->autoGenerateColumns) {
			$cols          = count($this->attributes);
			$this->columns = $cols >= self::GRID_WIDTH ? self::GRID_WIDTH : $cols;
		}

		if (isset($this->model) && !isset($this->form->model)) {
			$this->form->model = $this->model;
		}

		echo Html::beginTag($this->_tag, $this->options) . "\n";
	}

	protected function normalizeAttributes() {
	    $currentAction = FHtml::currentAction();

		//normalize attributes
		$arr = [];
		foreach ($this->attributes as $attribute => $value) {
			if (is_numeric($attribute)) {

				$arr = array_merge($arr, [$value => ['type' => FHtml::INPUT_TEXT, 'attribute' => $value]]);
			}
			else {
                if ($value instanceof \Closure) {
                    $result = call_user_func($value, $this->model, $attribute);
                    $arr = array_merge($arr, [$attribute => ['value' => $result, 'type' => FHtml::INPUT_RAW]]);
                } else if (is_object($value)) {
                    $arr = array_merge($arr, [$attribute => ['value' => $value, 'type' => FHtml::INPUT_RAW]]);
                }
                else {
                    if (is_array($value) && !key_exists('type', $value)) {
                        $value = array_merge($value, ['type' => FHtml::INPUT_RAW]);
                    }
                    $arr = array_merge($arr, [$attribute => $value]);
                }
			}
		}

		foreach ($arr as $attribute => $settings) {
			if (!isset($settings) || !is_array($settings)) {
				continue;
			}

			if (key_exists('visible', $settings) && $settings['visible'] == false) {
				unset($arr[$attribute]);
			}

			if (key_exists('readonly', $settings) && $settings['readonly'] == true) {
				$arr[$attribute]['type'] = FHtml::INPUT_READONLY;
			}

            if (key_exists('view_value', $settings) && $currentAction == 'view') {
                $arr[$attribute]['value'] = $settings['view_value'];
            } else if (key_exists('view', $settings) && $currentAction == 'view') {
                $arr[$attribute]['value'] = $settings['view'];
            }
		}

		$this->attributes = $arr;
	}

	/**
	 * Initializes the widget options
	 */
	protected function initOptions() {
		$this->_tag = ArrayHelper::remove($this->options, 'tag', 'fieldset');
		if (empty($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
	}

	/**
	 * Registers widget assets
	 */
	protected function registerAssets() {
		$view = $this->getView();
		//FormAsset::register($view);
		//$view->registerJs('jQuery("#' . $this->options['id'] . '").kvFormBuilder({});');
	}

	protected function isTableLayout() {
		return $this->display_type == FHtml::DISPLAY_TYPE_GRID || $this->display_type == FHtml::DISPLAY_TYPE_TABLE;
	}


	/**
	 * @inheritdoc
	 */
	public function run() {
		if (empty($this->attributes) || empty($this->visible)) {
			return;
		}

		if (!isset($this->open))
		    $this->open = FHtml::setting('form_table.default_opened', false);

        if (!isset($this->hide_field))
            $this->hide_field = FHtml::setting('form_field.default_hided', true);


        $action = FHtml::currentAction();

		if (FHtml::currentDevice()->isMobile()) {
			$this->_orientation = FActiveForm::TYPE_HORIZONTAL;
			$this->display_type = FHtml::DISPLAY_TYPE_DEFAULT;
			$this->edit_type    = FHtml::EDIT_TYPE_DEFAULT;
			$this->columns      = 1;
		}
		else {
			if (isset($this->form) && empty($this->type)) {
				$this->_orientation = $this->form->type;
			}
			else {
				$this->_orientation = $this->type;
			}

			if (isset($this->form) && empty($this->edit_type)) {
				$this->edit_type = $this->form->edit_type;
			}
			else {
				$this->edit_type = FHtml::EDIT_TYPE_INLINE;
			}

			if (isset($this->form) && empty($this->display_type)) {
				$this->display_type = $this->form->display_type;
			}
			else {
				$this->display_type = FHtml::DISPLAY_TYPE_TABLE;
			}
		}

		if ($this->isTableLayout()) {
			$this->formTag  = 'table';
			$this->rowTag   = 'tr';
			$this->fieldTag = 'td';

			if (FHtml::isViewAction($action) || $this->edit_type == FHtml::EDIT_TYPE_INLINE) {
				$this->formCSS = ' table-bordered table-responsive';
			}
			else {
				$this->formCSS = ' table-responsive';
			}
		}
		else {
			$this->formTag  = !empty($this->formTag) ? $this->formTag : 'div';
			$this->rowTag   = !empty($this->rowTag) ? $this->rowTag : 'div';
			$this->fieldTag = !empty($this->fieldTag) ? $this->fieldTag : 'div';
			$this->formCSS  = !empty($this->formCSS) ? $this->formCSS : '';
		}

		if (empty($this->title))
		    $this->open = null;
		else if (in_array(strtolower($this->title), ['group']))
		    $this->open = true;

		if (isset($this->open)) {

            $html = '<br/><div class="row">';
            $html .= '		<div class="col-md-12">';
            $html .= '			<div class="portlet light" style="box-shadow: none !important">';
            $html .= '				<div class="portlet-title tabbable-line hidden-print">';
            $html .= '					<div class="caption caption-md">';
            $html .= '						<i class="icon-globe theme-font hide"></i>';
            $html .= '						<span class="font-blue-madison bold uppercase" >';

            if (!empty($this->title)) {
                $html .= FHtml::t('common', $this->title);
            }

            $html .= '						</span>';
            $html .= '					</div>';
            $html .= '					<div class="tools pull-right">';
            $html .= '					    <span style="color:lightgrey"> ' . ($this->open ? '' :  ' +' .count($this->attributes) . ' ' . FHtml::t('common', 'items')) . '  </span>&nbsp;<a href=".collapse' . $this->options['id'] . '" data-toggle="collapse" class=" panel-collapse ' . ($this->open ? 'collapse' : 'expand') . ' " aria-expanded="true"></a>';
            $html .= '					</div>';
            $html .= '				</div>';
            $html .= '			</div>';
            $html .= '			<div style="' . ($this->open ? 'height:0px' : '') . '" class="portlet-body form ' . (!$this->open ? 'collapse' : 'collapsed') . ' collapse' . $this->options['id'] . '" aria-expanded="true" id="collapse' . $this->options['id'] . '">';
            $html .= '				<div class="form">';
            $html .= '					<div class="form-body">';
            $html .= '						<div class="tab-content">';
            $html .= '							<div class="tab-pane active">';

            $html .= $this->header;
            if (!empty($this->overview)) {
                $html .= "<div class='hint-block'>$this->overview</div>";;
            }
            $html .= $this->renderFieldSet();

            $html .= $this->footer;

            $html .= '							</div>';
            $html .= '						</div>';
            $html .= '					</div>';
            $html .= '				</div>';
            $html .= '			</div>';
            $html .= '		</div>';
            $html .= '</div>';

            $html .= Html::endTag($this->_tag);
            echo $html;
        } else {

            if (!empty($this->title))
                echo FHtml::showGroupHeader($this->title);

            echo $this->header;
            if (!empty($this->overview)) {
                echo "<div class='hint-block'>$this->overview</div>";
            }
            echo $this->renderFieldSet();
            echo Html::endTag($this->_tag);
            echo $this->footer;
        }

		parent::run();
	}

	/**
	 * Renders the field set
	 * @return string
	 */
	protected function renderFieldSet() {
		$content = '';
		$cols    = (is_int($this->columns) && $this->columns >= 1) ? $this->columns : 1;

		$index     = 0;
		$attrCount = count($this->attributes);
		$rows      = (float) ($attrCount / $cols) + 1;
		$rows      = ceil($rows);

		$names  = array_keys($this->attributes);
		$values = array_values($this->attributes);

		$width     = (int) (self::GRID_WIDTH / $cols);
		$skip      = ($attrCount == 1);
		$skip      = false;
		$form_tag  = $this->formTag;
		$form_css  = $this->formCSS;
		$row_tag   = $this->rowTag;
		$field_tag = $this->fieldTag;

		if ($cols == 1) {
			$labelWidth = $this->labelWidth;
		}
		elseif ($cols == 2) {
			$labelWidth = $this->labelWidth;
		}
		elseif ($cols == 3) {
			$labelWidth = $this->labelWidth;
		}
		elseif ($cols == 4) {
			$labelWidth = $this->labelWidth;
		}
		elseif ($cols == 12) {
			$labelWidth = 0;
		}
		else {
			$labelWidth = 0;
		}

		if ($this->_orientation == ActiveForm::TYPE_VERTICAL) {
			$labelWidth = 0;
		}

        //        Html::addCssClass($this->rowOptions, ($attrCount < $cols ? 'pull-left' : 'row') );
		//Html::addCssClass($this->rowOptions, ($attrCount < $cols ? 'row' : 'row') );

		$content .= $this->beginTag($form_tag, ['class' => 'col-md-12 col-xs-12' . $form_css]);

		for ($row = 1; $row <= $rows; $row++) {
			$content         .= $this->beginTag($row_tag, array_merge($this->rowOptions, ['class' => 'row']), $skip);
			$rowCurrentWidth = 0;
			for ($col = 1; $col <= $cols; $col++) {

				if ($index > ($attrCount - 1) || $rowCurrentWidth >= 12) {
					break;
				}

				$attribute = $names[$index];
				$settings  = $values[$index];

				if (!is_array($settings)) {
					$b        = $settings;
					$settings = ['value' => $b, 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW];
				}

				if (!key_exists('value', $settings)) {
					$settings['value'] = '';
				}

				$settings   = array_replace_recursive($this->attributeDefaults, $settings);
				$colOptions = ArrayHelper::getValue($settings, 'columnOptions', $this->columnOptions);

				$colWidth = (int) $width - $labelWidth;

				$colSpan = 1;
				if (isset($colOptions['colspan'])) {
					$colSpan = (int) ($colOptions['colspan']);
				}

				if ($this->isTableLayout()) {
					$colWidth        = $colSpan * $colWidth;
					$rowCurrentWidth = $rowCurrentWidth + $labelWidth + $colWidth;
				}
				else {
					if ($colSpan == 2) {
						$colWidth = $colWidth + $labelWidth;
					}
					elseif ($colSpan == 3) {
						$colWidth = $colWidth + $labelWidth + $colWidth;
					}
					elseif ($colSpan == 4) {
						$colWidth = $colWidth + $labelWidth + $colWidth + $labelWidth;
					}
					elseif ($colSpan > 1) {
						$colWidth = 10;
					}
					$rowCurrentWidth = $rowCurrentWidth + $labelWidth + $colWidth;
				}

				if ($this->readonly || FHtml::isViewAction()) {
					$labelwidth_xs = $labelWidth;
					$colWidth_xs   = $colWidth;
				}
				else {
					$labelwidth_xs = 12;
					$colWidth_xs   = 12;
				}

				$value1 = $settings['value'];
				if (!isset($value1) || $value1 === false) {

					$content .= "\t" . $this->beginTag($field_tag, ['class' => "col-$this->columnSize-$labelWidth col-xs-$labelwidth_xs", 'style' => 'padding:15px'], $skip) . "\n";
					$content .= "\t" . $this->endTag($field_tag, $skip) . "\n";
				}
				else {
					if (is_array($value1)) {
						$value1 = implode('<br/>', $value1);
					}
					if (is_string($value1) || is_numeric($value1)) {
						$settings['value'] = "<div class='col-md-12' style='padding:15px'>$value1</div>";
					}
					if ($labelWidth > 0 && $this->_orientation != FActiveForm::TYPE_INLINE) { // has Label
						if ($this->_orientation == FActiveForm::TYPE_HORIZONTAL) {
							$content .= "\t" . $this->beginTag($field_tag, [
									'class' => "col-$this->columnSize-$labelWidth col-xs-$labelwidth_xs form-label",
									'style' => 'padding:15px'
								], $skip) . "\n";
						}
						elseif ($this->_orientation == FActiveForm::TYPE_VERTICAL) {
							$content .= "\t" . $this->beginTag($field_tag, ['class' => "col-$this->columnSize-$labelWidth col-xs-$labelwidth_xs", 'style' => 'padding:5px'], $skip) . "\n";
						}

						$content .= key_exists('label', $settings) ? FHtml::t('common', $settings['label']) : FHtml::getFieldLabel($this->model, $attribute, true);
						$content .= "\t" . $this->endTag($field_tag, $skip) . "\n";
					}
				}

				Html::addCssClass($colOptions, "col-$this->columnSize-$colWidth");

				Html::addCssClass($colOptions, "col-xs-$colWidth_xs" . ($this->_orientation == FActiveForm::TYPE_HORIZONTAL ? '' : ' no-padding'));

				$content .= "\t" . $this->beginTag($field_tag, $colOptions, $skip) . "\n";
				if ($labelWidth == 0) {
					if ($this->_orientation == FActiveForm::TYPE_HORIZONTAL) {
						$content .= '<div class="form-label" style="padding:15px">' . (key_exists('label', $settings) ? FHtml::t('common', $settings['label']) : FHtml::getFieldLabel($this->model, $attribute, true)) . '</div>';
					}
					elseif ($this->_orientation == FActiveForm::TYPE_VERTICAL) {
						$content .= '<div class="bold" style="margin-top:10px; padding:5px">' . (key_exists('label', $settings) ? FHtml::t('common', $settings['label']) : FHtml::getFieldLabel($this->model, $attribute, true)) . '</div>';
					}
				}

				if (!empty($settings['attributes'])) {
					$content .= $this->renderSubAttributes($attribute, $settings, $index);
				} else {
                    $content .= "\t\t" . $this->parseInput($attribute, $settings, $index) . "\n";
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
	 * Render sub attributes
	 * @return string
	 */
	protected function renderSubAttributes($attribute, $settings, $index) {
		$label_tag   = 'div';
		$control_tag = 'div';
		$field_tag   = '';

		$content      = $this->getSubAttributesContent($attribute, $settings, $index);
		$labelOptions = ArrayHelper::getValue($settings, 'labelOptions', []);
		$label        = ArrayHelper::getValue($settings, 'label', '');
		if ($this->_orientation === ActiveForm::TYPE_INLINE) {
			Html::addCssClass($labelOptions, ActiveForm::SCREEN_READER);
		}
		elseif ($this->_orientation === ActiveForm::TYPE_VERTICAL) {
			Html::addCssClass($labelOptions, "control-label");
		}
		if ($this->_orientation !== ActiveForm::TYPE_HORIZONTAL) {
			return '<div class="kv-nested-attribute-block">' . "\n" . Html::label($label, null, $labelOptions) . "\n" . $content . "\n" . '</div>';
		}
		if (isset($this->form->formConfig['labelSpan'])) {
			$defaultLabelSpan = $this->form->formConfig['labelSpan'];
		}
		$labelSpan = ArrayHelper::getValue($settings, 'labelSpan', 3);
		Html::addCssClass($labelOptions, "col-{$this->columnSize}-{$labelSpan} control-label");
		$inputSpan    = self::GRID_WIDTH - $labelSpan;
		$rowOptions   = ['class' => 'kv-nested-attribute-block form-sub-attributes form-group'];
		$inputOptions = ['class' => "col-{$this->columnSize}-{$inputSpan}"];

		return Html::beginTag($field_tag, $rowOptions) . "\n" . Html::beginTag($label_tag, $labelOptions) . "\n" . $label . "\n" . Html::endTag($label_tag) . "\n" . Html::beginTag($control_tag, $inputOptions) . "\n" . $content . "\n" . Html::endTag($control_tag) . "\n" . Html::endTag($field_tag) . "\n";
	}

	/**
	 * Gets sub attribute markup content
	 * @return string
	 */
	protected function getSubAttributesContent($attribute, $settings, $index) {
		$label_tag   = 'div';
		$control_tag = 'div';
		$field_tag   = '';

		$subIndex             = 0;
		$defaultSubColOptions = ArrayHelper::getValue($settings, 'subColumnOptions', $this->columnOptions);
		$labelOptions         = ArrayHelper::getValue($settings, 'labelOptions', []);
		$content              = '';
		$content              .= "\t" . $this->beginTag($field_tag, $this->rowOptions) . "\n";
		$attrCount            = count($settings['attributes']);
		$cols                 = ArrayHelper::getValue($settings, 'columns', $attrCount);
		foreach ($settings['attributes'] as $subAttr => $subSettings) {
			$subColWidth = (int) (self::GRID_WIDTH / $cols);
			$subSettings = array_replace_recursive($this->attributeDefaults, $subSettings);
			if (key_exists('label', $subSettings) && !isset($subSettings['label'])) {
				$subSettings['label'] = false;
			}
			$subColOptions = ArrayHelper::getValue($subSettings, 'columnOptions', $defaultSubColOptions);
			if (isset($subColOptions['colspan'])) {
				$subColWidth = (int) $subColWidth * (int) ($subColOptions['colspan']);
				unset($subColOptions['colspan']);
			}
			Html::addCssClass($subColOptions, 'col-' . $this->columnSize . '-' . $subColWidth);
			$subSettings['columnOptions'] = $subColOptions;
			$content                      .= "\t\t" . $this->beginTag($control_tag, $subColOptions) . "\n";
			$content                      .= "\t\t\t" . $this->parseInput($subAttr, $subSettings, $index * 10 + $subIndex) . "\n";
			$subIndex++;
			$content .= "\t\t" . $this->endTag($control_tag) . "\n";
		}
		$content .= "\t" . $this->endTag($field_tag) . "\n";

		return $content;
	}

	/**
	 * Parses input for `INPUT_RAW` type
	 * @param string $attribute the model attribute
	 * @param string $settings  the column settings
	 * @param int    $index     the row index
	 * @return \kartik\form\ActiveField|mixed
	 * @throws InvalidConfigException
	 */
	protected function parseInput($attribute, $settings, $index) {
		if ($this->hasModel() && $settings instanceof \Closure) {
			call_user_func($settings, $this->model, $index, $this);

			return '';
		}
		if (strpos($attribute, ':') !== false) {
            $arr = FHtml::parseAttribute($attribute);
            $attribute = $arr['attribute'];
        }

		$type     = ArrayHelper::getValue($settings, 'type', self::INPUT_TEXT);
		$readonly = ArrayHelper::getValue($settings, 'readonly', $this->readonly);
		$required   = ArrayHelper::getValue($settings, 'required', false);
        $showValue   = FHtml::getFieldValue($settings, ['content', 'showValue', 'preview', 'html'], null);

        if ($readonly)
		    $type = self::INPUT_READONLY;


        if ($this->print == true || $readonly == true || $type == self::INPUT_READONLY || $this->edit_type == FHtml::EDIT_TYPE_VIEW || $readonly) {
            return ($settings['value'] instanceof \Closure) ? ($this->hasModel() ? FHtml::showModelFieldValue($this->model, $attribute) : '') : $settings['value'];
        }

        if (isset($settings['value'])) {
            $result = ($settings['value'] instanceof \Closure) ? call_user_func($settings['value'], ($this->hasModel() ? $this->model : $this->formName), $index, $this) : $settings['value'];
		}
		else {
			if ($this->edit_type == FHtml::EDIT_TYPE_INLINE || $type == self::INPUT_INLINE) {
				$result = $this->form->field($this->model, $attribute)->inline()->label(false);
			} else {
                $result = $this->hasModel() ? static::renderActiveInput($this->form, $this->model, $attribute, $settings) : static::renderInput("{$this->formName}[{$attribute}]", $settings, $this->model);
            }
        }

//        if (!$required && ($this->hide_field || $type == self::INPUT_INLINE) && !$readonly && !FHtml::getRequiredFields($this->model, $attribute) && !$readonly && FHtml::currentAction() != 'view') {
//            if (isset($showValue))
//                $result = FHtml::showModelFieldToogle($showValue, $result);
//            else
//                $result = FHtml::showModelFieldToogle($this->model, $attribute, $result);
//        }

        return $result;
	}

	/**
	 * Begins a tag markup based on orientation
	 * @return string
	 */
	protected function beginTag($tag, $options, $skip = false) {
		if ($this->_orientation !== ActiveForm::TYPE_INLINE && !$skip) {
			return Html::beginTag($tag, $options) . "\n";
		}

		return '';
	}

	/**
	 * Ends a tag markup based on orientation
	 * @return string
	 */
	protected function endTag($tag, $skip = false) {
		if ($this->_orientation !== ActiveForm::TYPE_INLINE && !$skip) {
			return Html::endTag($tag) . "\n";
		}

		return '';
	}

	/**
	 * Checks base config
	 * @throws InvalidConfigException
	 */
	protected function checkBaseConfig() {
		if (empty($this->form) && empty($this->formName)) {
			throw new InvalidConfigException("The 'formName' property must be set when you are not using with ActiveForm.");
		}
		if (!empty($this->form) && !$this->form instanceof \kartik\form\ActiveForm) {
			throw new InvalidConfigException("The 'form' property must be an instance of '\\kartik\\widgets\\ActiveForm' or '\\kartik\\form\\ActiveForm'.");
		}
		if (empty($this->attributes)) {
			throw new InvalidConfigException("The 'attributes' array must be set.");
		}
	}

	/**
	 * Checks config for Form widgets
	 * @throws InvalidConfigException
	 */
	protected function checkFormConfig() {
		if (!$this->hasModel() && empty($this->formName)) {
			throw new InvalidConfigException("Either the 'formName' has to be set or a valid 'model' property must be set extending from '\\yii\\base\\Model'.");
		}
		if (empty($this->formName) && (empty($this->form) || !$this->form instanceof \kartik\form\ActiveForm)) {
			throw new InvalidConfigException("The 'form' property must be set and must be an instance of '\\kartik\\form\\ActiveForm'.");
		}
	}

	/**
	 * Check if a valid model is set for the object instance
	 * @return boolean
	 */
	protected function hasModel() {
		return isset($this->model) && $this->model instanceof \yii\base\Model;
	}

	/**
	 * Renders active input based on the attribute settings.
	 * This includes additional markup like rendering content before
	 * and after input, and wrapping input in a container if set.
	 * @param \kartik\form\ActiveForm              $form      the form instance
	 * @param \yii\db\ActiveRecord|\yii\base\Model $model
	 * @param string                               $attribute the name of the attribute
	 * @param array                                $settings  the attribute settings
	 * @return \kartik\form\ActiveField
	 * @throws \yii\base\InvalidConfigException
	 */
	protected static function renderActiveInput($form, $model, $attribute, $settings) {
		$container = ArrayHelper::getValue($settings, 'container', []);
		$prepend   = ArrayHelper::getValue($settings, 'prepend', '');
		$append    = ArrayHelper::getValue($settings, 'append', '');
		$input     = static::renderRawActiveInput($form, $model, $attribute, $settings);
		$out       = $prepend . "\n" . $input . "\n" . $append;

		return empty($container) ? $out : Html::tag('div', $out, $container);
	}

	/**
	 * Renders normal form input based on the attribute settings.
	 * This includes additional markup like rendering content before
	 * and after input, and wrapping input in a container if set.
	 * @param string $attribute the name of the attribute
	 * @param array  $settings  the attribute settings
	 * @return string the form input markup
	 * @throws \yii\base\InvalidConfigException
	 */
	protected static function renderInput($attribute, $settings = [], $model = null) {
		$for          = '';
		$input        = static::renderRawInput($attribute, $settings, $for, $model);
		$label        = ArrayHelper::getValue($settings, 'label', false);
		$labelOptions = ArrayHelper::getValue($settings, 'labelOptions', []);
		Html::addCssClass($labelOptions, 'control-label');
		$type           = ArrayHelper::getValue($settings, 'type', self::INPUT_TEXT);
		$options        = ArrayHelper::getValue($settings, 'options', []);
		$label          = $label !== false && !empty($for) ? Html::label($label, $for, $labelOptions) . "\n" : '';
		$container      = ArrayHelper::getValue($settings, 'container', []);
		$prepend        = ArrayHelper::getValue($settings, 'prepend', '');
		$append         = ArrayHelper::getValue($settings, 'append', '');
		$inputContainer = ArrayHelper::getValue($settings, 'inputContainer', []);
		if (!empty($inputContainer)) {
			$input = Html::tag('div', $input, $inputContainer);
		}
		//$out = $prepend . "\n" . $label . $input . "\n" . $append;
		$out = "<td class='col-md-1'>$label</td><td>$input\n$append</td>";

		return empty($container) ? $out : Html::tag('div', $out, $container);
	}

	/**
	 * Renders raw active input based on the attribute settings
	 * @param \kartik\form\ActiveForm              $form      the form instance
	 * @param \yii\db\ActiveRecord|\yii\base\Model $model
	 * @param string                               $attribute the name of the attribute
	 * @param array                                $settings  the attribute settings
	 * @return \kartik\form\ActiveField
	 * @throws \yii\base\InvalidConfigException
	 */
	protected static function renderRawActiveInput($form, $model, $attribute, $settings) {
		$type       = ArrayHelper::getValue($settings, 'type', self::INPUT_TEXT);
		$i          = strpos($attribute, ']');
		$attribName = $i > 0 ? substr($attribute, $i + 1) : $attribute;

		$fieldConfig = ArrayHelper::getValue($settings, 'fieldConfig', []);
		$options     = ArrayHelper::getValue($settings, 'options', []);
		$label       = ArrayHelper::getValue($settings, 'label', null);
		$hint        = ArrayHelper::getValue($settings, 'hint', null);

		$field = $form->field($model, $attribute, $fieldConfig);

		if ($type === self::INPUT_TEXT || $type === self::INPUT_PASSWORD || $type === self::INPUT_TEXTAREA || $type === self::INPUT_HIDDEN || $type === self::INPUT_STATIC) {
			return static::getInput($field->$type($options), $label, $hint);
		}
		if ($type === self::INPUT_DROPDOWN_LIST || $type === self::INPUT_LIST_BOX || $type === self::INPUT_CHECKBOX_LIST || $type === self::INPUT_RADIO_LIST || $type === self::INPUT_MULTISELECT) {
			if (isset($settings['items'])) {
				$items = $settings['items'];
			}
			else {
				$items = FHtml::getComboArray('', FHtml::getTableName($model), $attribute);
			}

			return static::getInput($field->$type($items, $options), $label, $hint);

			//return $field->select($items)->label(false); //
		}

		if ($type === self::INPUT_HTML5) {
			$html5type = ArrayHelper::getValue($settings, 'html5type', 'text');

			return static::getInput($field->$type($html5type, $options), $label, $hint);
		}
		if ($type === self::INPUT_WIDGET) {
			$widgetClass = ArrayHelper::getValue($settings, 'widgetClass', []);
			if (empty($widgetClass) && !$widgetClass instanceof \yii\widgets\InputWidget) {
				throw new InvalidConfigException("A valid 'widgetClass' for '{$attribute}' must be setup and extend from 'yii\\widgets\\InputWidget'.");
			}

			return static::getInput($field->$type($widgetClass, $options), $label, $hint);
		}
		if ($type === self::INPUT_RAW || isset($settings['value'])) {
			return ArrayHelper::getValue($settings, 'value', '');
		}

		if ($type === FHtml::EDITOR_NUMERIC) {
			return $field->numeric()->label(false); //
		}
		if ($type === FHtml::EDITOR_DATE) {
			return $field->date()->label(false); //
		}
		if ($type === FHtml::EDITOR_DATETIME) {
			return $field->datetime()->label(false); //
		}
		if ($type === FHtml::EDITOR_BOOLEAN || $type === FHtml::INPUT_CHECKBOX || $type === FHtml::INPUT_RADIO) {
			return $field->checkbox()->label(false); //
		}
		if ($type === FHtml::EDITOR_HTML) {
			return $field->html()->label(false); //
		}

		if (!empty($type) && FHtml::isInArray($type, [
				'lookup',
				'select',
				'date',
				'numeric',
				'html',
				'datetime',
				'money',
				'email',
				'checkbox',
				'boolean',
				'slide',
				'url',
				'file',
				'image',
				'currency',
				'star',
				'inline',
				'inlineEdit',
				'static',
				'color',
				'textarea',
				'progress'
			])) {
			return $field->$type()->label(false); //
		}

		return $field->inline()->label(false);
	}

	/**
	 * Renders raw form input based on the attribute settings
	 * @param string $attribute the name of the attribute
	 * @param array  $settings  the attribute settings
	 * @return string the form input markup
	 * @throws \yii\base\InvalidConfigException
	 */
	protected static function renderRawInput($attribute, $settings = [], &$id, $model = null) {
		$type       = ArrayHelper::getValue($settings, 'type', self::INPUT_TEXT);
		$i          = strpos($attribute, ']');
		$attribName = $i > 0 ? substr($attribute, $i + 1) : $attribute;

		$value   = ArrayHelper::getValue($settings, 'value', null);
		$options = ArrayHelper::getValue($settings, 'options', []);
		$id      = str_replace(['[]', '][', '[', ']', ' '], ['', '-', '-', '', '-'], $attribute);
		$id      = strtolower($id);
		if ($type === self::INPUT_WIDGET) {
			$id                       = empty($options['options']['id']) ? $id : $options['options']['id'];
			$options['options']['id'] = $id;
		}
		else {
			$id            = empty($options['id']) ? $id : $options['id'];
			$options['id'] = $id;
		}
		if ($type === self::INPUT_STATIC) {
			Html::addCssClass($options, 'form-control-static');

			return Html::tag('p', $value, $options);
		}
		Html::addCssClass($options, 'form-control');
		if ($type === self::INPUT_TEXT || $type === self::INPUT_PASSWORD || $type === self::INPUT_TEXTAREA || $type === self::INPUT_FILE || $type === self::INPUT_HIDDEN) {
			return Html::$type($attribute, $value, $options);
		}
		if ($type === self::INPUT_DROPDOWN_LIST || $type === self::INPUT_LIST_BOX || $type === self::INPUT_CHECKBOX_LIST || $type === self::INPUT_RADIO_LIST || $type === self::INPUT_MULTISELECT) {
			if (isset($settings['items'])) {
				$items = ArrayHelper::getValue($settings, 'items', []);
			}
			else {
				$items = FHtml::getComboArray('', FHtml::getTableName($model), $attribute);
			}

			return Html::$type($attribute, $value, $items, $options);
		}
		if ($type === self::INPUT_CHECKBOX || $type === self::INPUT_RADIO) {
			$enclosedByLabel = ArrayHelper::getValue($settings, 'enclosedByLabel', true);
			$checked         = !empty($value) && ($value !== false) ? true : false;
			$out             = Html::$type($attribute, $checked, $options);

			return $enclosedByLabel ? "<div class='{$type}'>{$out}</div>" : $out;
		}
		if ($type === self::INPUT_HTML5) {
			$html5type = ArrayHelper::getValue($settings, 'html5type', 'text');

			return Html::input($type, $attribute, $value, $options);
		}
		if ($type === self::INPUT_WIDGET) {
			$widgetClass = ArrayHelper::getValue($settings, 'widgetClass', []);
			if (empty($widgetClass) && !$widgetClass instanceof yii\widgets\InputWidget) {
				throw new InvalidConfigException("A valid 'widgetClass' for '{$attribute}' must be setup and extend from 'yii\\widgets\\InputWidget'.");
			}
			$options['name']  = $attribute;
			$options['value'] = $value;

			return $widgetClass::widget($options);
		}
		if ($type === self::INPUT_RAW) {
			return ArrayHelper::getValue($settings, 'value', '');
		}
	}

	/**
	 * Generates the active field input by parsing the label and hint
	 * @param ActiveField $field
	 * @param string      $label the label for the field
	 * @param string      $hint  the hint for the field
	 * @return ActiveField
	 */
	protected static function getInput($field, $label = null, $hint = null) {
		if ($label !== null) {
			$field = $field->label($label);
		}
		if ($hint !== null) {
			$field = $field->hint($hint);
		}

		$field = $field->label(false);

		return $field;
	}
}