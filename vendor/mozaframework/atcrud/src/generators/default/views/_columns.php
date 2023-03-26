<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\Helper;
use common\components\FHtml;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $generator mozaframework\atcrud\generators\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();
$tableSchema = $generator->getTableSchema();

$previewAttributes = FHtml::FIELDS_PREVIEW;
$hiddenAttributes = FHtml::FIELDS_HIDDEN;
$countAttributes = FHtml::FIELDS_COUNT;
$groupAttributes = FHtml::getFIELDS_GROUP();
$uploadAttributes = FHtml::FIELDS_UPLOAD;
$priceAttributes = FHtml::FIELDS_PRICE;
$dateAttributes = FHtml::FIELDS_DATE;
$commonAttributes = FHtml::FIELDS_COMMON;
$imageAttributes = FHtml::FIELDS_IMAGES;
$booleanAttributes = FHtml::FIELDS_BOOLEAN;


echo "<?php\n";
?>

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$object_type = '<?= str_replace('-', '_', Inflector::camel2id(StringHelper::basename($generator->modelClass))) ?>';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    /*
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '30px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view', ['model' => $model, 'print' => false]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
*/
<?php
$width_count = 0;

// Dont generate system fields
$system_fields = FHtml::COLUMNS_SYSTEMS;

// Dont display hidden fields in Grid (comment out)
$hidden_fields = array_merge(FHtml::FIELDS_HIDDEN, FHtml::FIELDS_HTML, FHtml::FIELDS_COUNT, FHtml::FIELDS_COMMON, ['id']);
$visible_fields = FHtml::FIELDS_VISIBLE;
$previewAttributes = FHtml::FIELDS_PREVIEW;
$hiddenAttributes = FHtml::FIELDS_HIDDEN;
$countAttributes = FHtml::FIELDS_COUNT;
$groupAttributes = FHtml::getFIELDS_GROUP();
$uploadAttributes = FHtml::FIELDS_UPLOAD;
$priceAttributes = FHtml::FIELDS_PRICE;
$dateAttributes = FHtml::FIELDS_DATE;
$commonAttributes = FHtml::FIELDS_COMMON;

$htmlAttributes =  FHtml::FIELDS_HTML;
$textareaAttributes = FHtml::FIELDS_TEXTAREA;
$textareaSmallAttributes = FHtml::FIELDS_TEXTAREASMALL;
$lookupAttributes = FHtml::FIELDS_LOOKUP;
$dateAttributes = FHtml::FIELDS_DATE;
$datetimeAttributes = FHtml::FIELDS_TIME;
$rateAttributes = FHtml::FIELDS_RATE;

$booleanAttributes = FHtml::FIELDS_BOOLEAN;
$fileAttributes = FHtml::FIELDS_FILES;
$imageAttributes = FHtml::FIELDS_IMAGES;
$percentAttributes = FHtml::FIELDS_PERCENT;
$image_field_index = 0;

foreach ($generator->getGridColumnNames() as $name) {
    if(!FHtml::isInArray($name, $system_fields)) {
        $is_comment = '';

        $width = 1;
        $width_px = '';

        if (FHtml::isInArray($name, array_merge($visible_fields, ['image']))) {
            $is_comment = '';
        } else {
            $is_comment = '//';
            if ($width_count > 12
                || FHtml::isInArray($name, $hidden_fields)
            ) {
                $is_comment = '//';
            }
        }

        $show_value = false;

        $column = $tableSchema->columns[$name];
        $commentArray = FHtml::toArrayFromDbComment($column->comment);
        $lookup_key = isset($commentArray['lookup']) ? $commentArray['lookup'] : $tableSchema->name;
        $lookup_table = StringHelper::startsWith($lookup_key, '@') ? str_replace('@', '', $lookup_key) : $lookup_key;

        $editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';
        if ($editor == 'hidden')
            $is_comment = '//';

        $show_type = isset($commentArray['show']) ? $commentArray['show'] : '';
        if ($show_type == 'no' || $show_type == 'false' || $show_type == 'hidden') {
            $is_comment = '//';
            $show_type = 'HIDDEN';
        } else {
            $show_type = strtolower($show_type);
        }

        if (isset($commentArray['grid']) && $commentArray['grid'] == 'hidden')
        {
            $is_comment = '//';
        }
        if ($image_field_index > 0 && ($editor == 'image' || FHtml::isInArray($name, $imageAttributes)))
            $is_comment = '//';

//define start//
        $start = "    ".$is_comment."[ \n";
        $class = "        ".$is_comment."'class' => FHtml::getColumnClass(\$object_type, '$name', \$form_type),\n";
        $visible ="        ".$is_comment."'visible' => FHtml::isVisibleInGrid(\$object_type, '$name', \$form_type),\n";

        $format = "        ".$is_comment."'format' => 'raw',\n";
        $editableOptions = "";
        $attribute = "";
        $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
        $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
        $contentOptions = "";
        $value = "";
        $filterType = "";
        $filter = "";
        $filterInputOptions = "";
        $filterWidgetOptions = "";
        $header = '';//"        ".$is_comment."'header' => FHtml::t('common', ". $generator->generateString($name) ."),\n";
        $footer = "";

        $end ="    ".$is_comment."],\n";
//define end//
//check and modify//
        if($column->dbType == 'tinyint(1)')
        {
            if(FHtml::isInArray($name, $booleanAttributes) || $column->size == 1)
            {
                $class ="        ".$is_comment."'class' => 'kartik\grid\BooleanColumn',\n";
                $width_px = '50px';
                $hAlign="        ".$is_comment."'hAlign' => 'center',\n";
                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
            }else{
                $width_px = '50px';
                $hAlign="        ".$is_comment."'hAlign' => 'center',\n";
                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
            }
        }
        else
        {
            if ((strpos($column->dbType, 'varchar') !== false))
            {
                if($editor == 'image' || FHtml::isInArray($name, $imageAttributes))
                {
                    if (empty($is_comment))
                        $image_field_index += 1;
                    $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showImage(\$model); }, \n";
                    $format="        ".$is_comment."'format' => 'html',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'center',\n";
                    $width = 1;
                    $width_px = '50px';

                    $editableOptions ="        ".$is_comment."'editableOptions' => [                       
                            ".$is_comment."'size' => 'md',
                            ".$is_comment."'inputType' => \kartik\\editable\Editable::INPUT_TEXT,
                            ".$is_comment."'widgetClass' => 'kartik\datecontrol\InputControl',
                            ".$is_comment."'options' => [
                                ".$is_comment."'options' => [
                                    ".$is_comment."'pluginOptions' => [
                                        ".$is_comment."'autoclose' => true
                                    ".$is_comment."]
                                ".$is_comment."]
                            ".$is_comment."]
                        ".$is_comment."],\n";
                }
                elseif ($editor == 'date' || strpos($column->dbType, 'date') !== false || (strpos($column->dbType, 'varchar') !== false && $column->size == 11)) {
                    $format="        ".$is_comment."'format' => 'raw', // date \n";
                    $editableOptions ="        ".$is_comment."'editableOptions' => [                       
                            ".$is_comment."'size' => 'md',
                            ".$is_comment."'inputType' => \kartik\\editable\Editable::INPUT_WIDGET,
                            ".$is_comment."'widgetClass' => 'kartik\datecontrol\DateControl',
                            ".$is_comment."'options' => [
                                ".$is_comment."'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                                ".$is_comment."'displayFormat' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'dd M yyyy'),
                                ".$is_comment."'saveFormat' => 'php:Y-m-d',
                                ".$is_comment."'options' => [
                                    ".$is_comment."'pluginOptions' => [
                                        ".$is_comment."'autoclose' => true
                                    ".$is_comment."]
                                ".$is_comment."]
                            ".$is_comment."]
                        ".$is_comment."],\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'right',\n";
                    $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
                    $width_px = '50px';
                }
                else if($editor == 'color' || strpos($name, 'color') !== false)
                {
                    $show_type = empty($show_type) ? 'COLOR' : strtoupper($show_type);

                    $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_$show_type, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                    $format="        ".$is_comment."'format' => 'raw',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
                    $width = 1;
                    $width_px = '20px';
                    $filterType="        ".$is_comment."'filterType' => GridView::FILTER_COLOR, \n";

                    $t = $is_comment;
                    $is_comment = '//';
                    $editableOptions ="        ".$is_comment."'editableOptions' => [\n
                            ".$is_comment."'size' => 'md',
                            ".$is_comment."'widgetClass' => 'kartik\widgets\ColorInput',                          
                            ".$is_comment."'inputType' => \kartik\\editable\Editable::INPUT_COLOR,] \n";

                    $is_comment = $t;

                }
                else if($editor == 'select' || FHtml::isInArray($name, $lookupAttributes) || $name == 'categoryid' || strpos($name, '_id') !== false || strpos($name, 'type') !== false || strpos($name, 'status') !== false || strpos($name, 'lang') !== false)
                {
                    $show_type = empty($show_type) ?  (StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL') : strtoupper($show_type);

                    $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_$show_type, '". $lookup_key . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                    $format="        ".$is_comment."'format' => 'raw',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
                    if($column->size > 100 || FHtml::isInArray($name, ['category_id']))
                    {
                        $width_px = '100px';
                    } else
                    {
                        $width_px = '80px';
                    }
                    $filterType="        ".$is_comment."'filterType' => GridView::FILTER_SELECT2, \n";
                    $filterWidgetOptions = "        ". $is_comment."'filterWidgetOptions' => [
                            ".$is_comment."'pluginOptions' => ['allowClear' => true],
                            ".$is_comment."],\n";

                    $filter = "        ".$is_comment."'filter' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name'),\n";
                    $filterInputOptions ="        ". $is_comment."'filterInputOptions' => ['placeholder' => ''],\n";

                    $t = $is_comment;
                    $is_comment = '//';
                    $editableOptions ="        ".$is_comment."'editableOptions' => function (\$model, \$key, \$index, \$widget) {
                                    ".$is_comment."\$fields = FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name');
                                    ".$is_comment."return [
                                    ".$is_comment."'inputType' => 'dropDownList',
                                    ".$is_comment."'displayValueConfig' => \$fields,
                                    ".$is_comment."'data' => \$fields
                                    ".$is_comment."];
                                    ".$is_comment."},\n";
                    $is_comment = $t;
                    $editableOptions = "";
                    //$class ="        ".$is_comment."'class' => 'kartik\grid\DataColumn',\n";
                }
                else
                {
                    $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
                    if($column->size > 300 || FHtml::isInArray($name, ['name', 'overview', 'description', 'title']))
                    {
                        if (count($generator->getGridColumnNames()) > 14)
                            $width = 2;
                        else
                            $width = 5;
                    } else
                    {
                        $width = 1;
                    }
                    if ($column->size == 10 || $column->size == 20 || $column->size == 50 || $column->size == 100) {
                        $filterType="        ".$is_comment."'filterType' => GridView::FILTER_SELECT2, \n";
                        $filterWidgetOptions = "        ". $is_comment."'filterWidgetOptions' => [
                            ".$is_comment."'pluginOptions' => ['allowClear' => true],
                            ".$is_comment."],\n";

                        $filter = "        ".$is_comment."'filter' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name'),\n";
                        $filterInputOptions ="        ". $is_comment."'filterInputOptions' => ['placeholder' => ''],\n";

                        $editableOptions = "        " . $is_comment . "'editableOptions' => function (\$model, \$key, \$index, \$widget) {
                                    " . $is_comment . "\$fields = FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name');
                                    " . $is_comment . "return [
                                    " . $is_comment . "'inputType' => 'dropDownList',
                                    " . $is_comment . "'displayValueConfig' => \$fields,
                                    " . $is_comment . "'data' => \$fields
                                    " . $is_comment . "];
                                    " . $is_comment . "},\n";

                    } else {
                        $editableOptions = "        " . $is_comment . "'editableOptions' => [                       
                            " . $is_comment . "'size' => 'md',
                            " . $is_comment . "'inputType' => \kartik\\editable\Editable::INPUT_TEXT,
                            " . $is_comment . "'widgetClass' => 'kartik\datecontrol\InputControl',
                            " . $is_comment . "'options' => [
                                " . $is_comment . "'options' => [
                                    " . $is_comment . "'pluginOptions' => [
                                        " . $is_comment . "'autoclose' => true
                                    " . $is_comment . "]
                                " . $is_comment . "]
                            " . $is_comment . "]
                        " . $is_comment . "],\n";


                    }
                }

                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
            }
            elseif ($editor == 'date' || strpos($column->dbType, 'date') !== false || (strpos($column->dbType, 'varchar') !== false && $column->size == 11)) {
                $format="        ".$is_comment."'format' => 'raw', // date \n";
                $editableOptions ="        ".$is_comment."'editableOptions' => [                       
                            ".$is_comment."'size' => 'md',
                            ".$is_comment."'inputType' => \kartik\\editable\Editable::INPUT_WIDGET,
                            ".$is_comment."'widgetClass' => 'kartik\datecontrol\DateControl',
                            ".$is_comment."'options' => [
                                ".$is_comment."'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                                ".$is_comment."'displayFormat' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
                                ".$is_comment."'saveFormat' => 'php:Y-m-d',
                                ".$is_comment."'options' => [
                                    ".$is_comment."'pluginOptions' => [
                                        ".$is_comment."'autoclose' => true
                                    ".$is_comment."]
                                ".$is_comment."]
                            ".$is_comment."]
                        ".$is_comment."],\n";
                $hAlign="        ".$is_comment."'hAlign' => 'right',\n";
                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
                $width_px = '50px';
            }
            elseif($editor == 'dedimal' || $editor == 'currency' || $editor == 'money' || strpos($column->dbType, 'double') !== false || strpos($column->dbType, 'currency') !== false || strpos($column->dbType, 'decimal') !== false){
                $width = 1;
                $width_px = '50px';
                $hAlign="        ".$is_comment."'hAlign' => 'right',\n";
                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
                $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_DECIMAL, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                $editableOptions ="        ".$is_comment."'editableOptions' => [                       
                            ".$is_comment."'size' => 'md',
                            ".$is_comment."'inputType' => '\\kartik\\money\\MaskMoney', //\\kartik\\editable\\Editable::INPUT_SPIN,
                            ".$is_comment."'options' => [
                                ".$is_comment."'pluginOptions' => [
                                    ".$is_comment."'min' => 0, 'max' => 50000000000
                                ".$is_comment."]
                            ".$is_comment."]
                        ".$is_comment."],\n";
                $format = "        ".$is_comment."'format' => 'raw',//['decimal', 2],\n";

            }
            elseif(strpos($column->dbType, 'bigint') !== false || strpos($column->dbType, 'int') !== false || strpos($column->dbType, 'tinyint') !== false){
                if (FHtml::isInArray($column->name, ['id']))  {
                    $value ="        ".$is_comment."'value' => function(\$model) { return '<b>' . FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_NUMBER, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."') . '</b>' ; }, \n";
                    $format="        ".$is_comment."'format' => 'html',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'center',\n";
                    $vAlign = "        " . $is_comment . "'vAlign' => 'middle',\n";
                    $width_px = '50px';

                    $class ="        ".$is_comment."'class' => 'kartik\grid\DataColumn',\n";
                } else if ($editor == 'select' || $name == 'categoryid' || FHtml::isInArray($name, $lookupAttributes) || strpos($name, '_id') !== false || strpos($name, 'type') !== false || strpos($name, 'status') !== false || strpos($name, 'lang') !== false)  {
                    $show_type = empty($show_type) ?  (StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL') : strtoupper($show_type);
                    $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model->$name, FHtml::SHOW_$show_type, '$lookup_key', '$name', '$column->dbType', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                    $format="        ".$is_comment."'format' => 'raw',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
                    $vAlign = "        " . $is_comment . "'vAlign' => 'middle',\n";
                    $width = 1;
                    $filterType="        ".$is_comment."'filterType' => GridView::FILTER_SELECT2, \n";
                    $filterWidgetOptions = "        ". $is_comment."'filterWidgetOptions' => [
                            ".$is_comment."'pluginOptions' => ['allowClear' => true],
                            ".$is_comment."],\n";

                    $filter = "        ".$is_comment."'filter' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name'),\n";
                    $filterInputOptions ="        ". $is_comment."'filterInputOptions' => ['placeholder' => ''],\n";

                    $editableOptions ="        ".$is_comment."'editableOptions' => function (\$model, \$key, \$index, \$widget) {
                                    ".$is_comment."\$fields = FHtml::getComboArray('$lookup_key', '$lookup_table', '$name', true, 'id', 'name');
                                    ".$is_comment."return [
                                    ".$is_comment."'inputType' => 'dropDownList',
                                    ".$is_comment."'displayValueConfig' => \$fields,
                                    ".$is_comment."'data' => \$fields
                                    ".$is_comment."];
                                    ".$is_comment."},\n";
                    //$editableOptions = '';
                    //$class ="        ".$is_comment."'class' => 'kartik\grid\DataColumn',\n";
                } elseif ($editor == 'currency' || $editor == 'money' )  {
                    //$value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_CURRENCY, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                    $format="        ".$is_comment."'format' => 'raw',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'left',\n";
                    $vAlign = "        " . $is_comment . "'vAlign' => 'middle',\n";
                    $width = 1;
//
                    //$editableOptions = '';
                    //$class ="        ".$is_comment."'class' => 'kartik\grid\DataColumn',\n";
                } elseif ( FHtml::isInArray($name, $countAttributes))  {
                    //$value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_NUMBER, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                    $format="        ".$is_comment."'format' => 'raw',\n";
                    $hAlign="        ".$is_comment."'hAlign' => 'right',\n";
                    $vAlign = "        " . $is_comment . "'vAlign' => 'middle',\n";
                    $width_px = '50px';

                    $class ="        ".$is_comment."'class' => 'kartik\grid\DataColumn',\n";
                } else {
                    $width = 1;
                    $width_px = '50px';
                    $hAlign = "        " . $is_comment . "'hAlign' => 'right',\n";
                    $vAlign = "        " . $is_comment . "'vAlign' => 'middle',\n";
                    //$value = "        " . $is_comment . "'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_NUMBER, '" . $tableSchema->name . "', '" . $name . "', '" . $column->dbType . "', '" . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . "'); }, \n";
                    $editableOptions = "        " . $is_comment . "'editableOptions' => [                       
                            " . $is_comment . "'size' => 'md',
                            " . $is_comment . "'inputType' => \\kartik\\editable\\Editable::INPUT_SPIN, //'\\kartik\\money\\MaskMoney',
                            " . $is_comment . "'options' => [
                                " . $is_comment . "'pluginOptions' => [
                                    " . $is_comment . "'min' => 0, 'max' => 50000000000, 'precision' => 0, 
                                " . $is_comment . "]
                            " . $is_comment . "]
                        " . $is_comment . "],\n";
                    $format = "        " . $is_comment . "'format' => 'raw', //['decimal', 0],\n";
                }

            }
            else{
                $hAlign="        ".$is_comment."'hAlign' => 'right',\n";
                $vAlign="        ".$is_comment."'vAlign' => 'middle',\n";
                $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", '', '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
                $width = 1;
            }
        }

        $attribute="        ".$is_comment."'attribute' => '" . $name . "',\n";

        if (strpos($name, 'is_') !== false || strpos($column->dbType, 'bool') || strpos($column->dbType, 'tinyint(1)')) {
            $class ="        ".$is_comment."'class' => 'kartik\grid\BooleanColumn',\n";
            $value ="        ".$is_comment."'value' => function(\$model) { return FHtml::showContent(\$model-> " . $name . ", FHtml::SHOW_BOOLEAN, '". $tableSchema->name . "', '". $name ."', '". $column->dbType ."', '". Inflector::camel2id(StringHelper::basename($generator->modelClass))."'); }, \n";
            //$filterType="        ".$is_comment."'filterType' => 'checkbox', \n";
            $width = 1;
            $width_px = '20px';
        }

        if ($width_px == '') {
            $contentOptions = "        " . $is_comment . "'contentOptions' => ['class' => 'col-md-" . $width . " nowrap'],\n";
        }
        else
        {
            $width_px =  "        " . $is_comment . "'width' => '". $width_px . "',\n";
        }

        if(!FHtml::isInArray($name, $hidden_fields)){
            $width_count += $width;
        }

//check and modify end//
//generate code//

        echo $start;

        //echo $class;

//        if(strlen($format) != 0)
//            echo $format;

        if(strlen($header) != 0)
            echo $header;

        if(strlen($footer) != 0)
            echo $footer;

        if(strlen($attribute) != 0)
            echo $attribute;

//        if(strlen($visible) != 0)
//            echo $visible;

        if(strlen($value) != 0 && $show_value)
            echo $value;

//        if(strlen($hAlign) != 0)
//            echo $hAlign;

//        if(strlen($vAlign) != 0)
//            echo $vAlign;

        if(strlen($width_px) != 0)
            echo $width_px;

        if(strlen($filterType) != 0)
            echo $filterType;

//        if(strlen($filterWidgetOptions) != 0)
//            echo $filterWidgetOptions;

//        if(strlen($filterInputOptions) != 0)
//            echo $filterInputOptions;

        if(strlen($filter) != 0)
            echo $filter;

        if(strlen($contentOptions) != 0)
            echo $contentOptions;

//        if(strlen($editableOptions) != 0)
//            echo $editableOptions;

        echo $end;
//generate end//
    }
}
?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' => [
            'view' => FHtml::isInRole('', 'view', $currentRole),
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'View'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'Update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'Delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('message', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete?')
        ],
    ],
];