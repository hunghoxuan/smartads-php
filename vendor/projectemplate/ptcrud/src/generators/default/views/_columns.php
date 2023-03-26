<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use projectemplate\ptcrud\Helper;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();
$tableSchema = $generator->getTableSchema();

$image_fields = array(
    'thumbnail',
    'image',
    'icon',
    'logo',
    'avatar',
    'cover'
);

$folder_name = Inflector::camel2id($modelClass);
$lower_name = str_replace('-', '_', $folder_name);

$lookup = array();
$dropdown = array();

foreach ($tableSchema->columns as $column) :
    if (!empty($column->comment)) {
        $check_keyword = Helper::keyword($column->comment);
        if ($check_keyword !== false) {
            $keyword = $check_keyword;
            if ($keyword == Helper::DROPDOWN_KEYWORD) {
                $dropdown[] = $column->name;
            }
            if ($keyword == Helper::LOOKUP_KEYWORD) {
                $lookup[$column->name] = Helper::lookupDataFromDbComment($column->comment, $keyword);
            }
        }
    }
endforeach;

echo "<?php\n";
?>

use yii\helpers\Url;
use common\components\FHtml;

return [
[
'class' => 'kartik\grid\CheckboxColumn',
],
//[
//    'class' => 'kartik\grid\SerialColumn',
//    'width' => '30px',
//],
<?php
$width_count = 0;
$hidden_fields = array('application_id', 'id', 'password');
$short_space = "        ";
$long_space = "            ";

foreach ($generator->getColumnNames() as $name) {
    $is_comment = '';


    $column = $tableSchema->columns[$name];

    //hide fields: width = 0
    if ($width_count > 9
        || Helper::checkHiddenField($name, $hidden_fields)
        || $column->dbType == 'varchar(500)'
        || $column->dbType == 'varchar(1000)'
        || $column->dbType == 'text'
    ) {
        $is_comment = '//';
        $width = 0;
    } else {  //default width = 2
        $width = 2;
    }

//define
    $start = "    " . $is_comment . "[\n";
    $class = $short_space . $is_comment . "'class' => 'kartik\grid\DataColumn',\n";
    $format = "";
    $editableOptions = "";
    $attribute = "";
    $vAlign = $short_space . $is_comment . "'vAlign' => 'middle',\n";
    $hAlign = $short_space . $is_comment . "'hAlign' => 'center',\n";
    $contentOptions = "";
    $value = "";
    $end = "    " . $is_comment . "],\n";

//check and modify//
    //bolean
    if ($column->dbType == 'tinyint(1)') {
        $class = $short_space . $is_comment . "'class' => 'kartik\grid\BooleanColumn',\n";
        $width = $is_comment == "" ? 1 : 0;
    } else {
        //varchar
        if ((strpos($column->dbType, 'varchar') !== false)) {
            //image
            if (in_array($name, $image_fields)) {
                $value = $short_space . $is_comment . "'value' => function (\$model) {\n";
                $value .= $long_space . $is_comment . "return FHtml::showImageThumbnail(\$model->" . $name . ", false, '" . $folder_name . "');\n";
                $value .= $short_space . $is_comment . "},\n";
                $format = $short_space . $is_comment . "'format' => 'html',\n";
                $width = $is_comment == "" ? 1 : 0;
            } else {
                if ($column->size > 20) {
                    $width = $is_comment == "" ? 2 : 0;
                    //default alignment for varchar
                    $hAlign = $short_space . $is_comment . "'hAlign' => 'left',\n";
                }
            }
        } //date
        elseif ($column->dbType == 'date' || $column->dbType == 'datetime' || $column->dbType == 'time') {
            $width = $is_comment == "" ? 1 : 0;
        }
    }


    //atttribute
    $attribute = $short_space . $is_comment . "'attribute' => '" . $name . "',\n";

    //lookup
    if (array_key_exists($name, $lookup)) {
        if (count($lookup[$name]) > 0) {
            $label = BaseInflector::humanize($name, true);
            $prompt = $generator->generateString("Select $label");

            $value = $short_space . $is_comment . "'value' => function (\$model) {\n";
            $value .= $long_space . $is_comment . "return " . ltrim($generator->modelClass, '\\') . "::lookupLabel('" . $lookup[$name]['table'] . "', '" . $lookup[$name]['key'] . "', \$model->" . $name . ", '" . $lookup[$name]['value'] . "');\n";
            $value .= $short_space . $is_comment . "},\n";
            $value .= $short_space . $is_comment . "'filter' => " . ltrim($generator->modelClass, '\\') . "::lookupData('" . $lookup[$name]['table'] . "', '" . $lookup[$name]['key'] . "', '" . $lookup[$name]['value'] . "'),\n";
            $value .= $short_space . $is_comment . "'filterType' => \kartik\grid\GridView::FILTER_SELECT2,\n";
            $value .= $short_space . $is_comment . "'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],\n";
            $value .= $short_space . $is_comment . "'filterInputOptions' => ['placeholder' => $prompt],\n";
            $format = $short_space . $is_comment . "'format' => 'html',\n";
        }
    } //dropdown
    elseif (in_array($name, $dropdown)) {
        $value = $short_space . $is_comment . "'value' => function (\$model) {\n";
        $value .= $long_space . $is_comment . "return " . ltrim($generator->modelClass, '\\') . "::" . $name . "Label(\$model->" . $name . ");\n";
        $value .= $short_space . $is_comment . "},\n";
        $value .= $short_space . $is_comment . "'filter' => " . ltrim($generator->modelClass, '\\') . "::" . $name . "Array(),\n";
        $format = $short_space . $is_comment . "'format' => 'html',\n";
    }

    $contentOptions = $short_space . $is_comment . "'contentOptions' => ['class' => 'col-md-" . $width . " nowrap'],\n";

    $width_count += $width;

//check and modify end//
//generate columns code//
    echo $start;
    echo $class;
    if (strlen($attribute) != 0)
        echo $attribute;
    if (strlen($value) != 0)
        echo $value;
    if (strlen($format) != 0)
        echo $format;
    if (strlen($hAlign) != 0)
        echo $hAlign;
    if (strlen($vAlign) != 0)
        echo $vAlign;
    if (strlen($editableOptions) != 0)
        echo $editableOptions;
    if (strlen($contentOptions) != 0)
        echo $contentOptions;
    echo $end;
}
//generate action column
?>
[
'class' => 'kartik\grid\ActionColumn',
'dropdown' => false,
'vAlign' => 'middle',
'urlCreator' => function ($action, $model) {
return Url::to([$action, <?= $urlParams ?>]);
},
'viewOptions' => ['role' => 'modal-remote', 'title' => Yii::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
'updateOptions' => ['role' => $this->params['displayType'], 'title' => Yii::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
'deleteOptions' => [
'role' => 'modal-remote',
'title' => Yii::t('common', 'title.delete'),
'data-confirm' => false,
'data-method' => false,// for overide yii data api
'data-request-method' => 'post',
'data-toggle' => 'tooltip',
'data-confirm-title' => 'Are you sure?',
'data-confirm-message' => 'Are you sure want to delete this item'
],
],
];