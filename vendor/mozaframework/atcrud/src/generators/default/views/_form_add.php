<?php

use mozaframework\atcrud\Helper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $generator mozaframework\atcrud\generators\Generator */
/* @var $model \yii\db\ActiveRecord */

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
$modelName = $generator->getTableSchema()->name;
$modulePath = Inflector::camel2id(StringHelper::basename($generator->modelClass));

$previewAttributes = FHtml::FIELDS_PREVIEW;
$hiddenAttributes = FHtml::FIELDS_HIDDEN;
$countAttributes = FHtml::FIELDS_COUNT;
$groupAttributes = FHtml::getFIELDS_GROUP();
$uploadAttributes = FHtml::FIELDS_UPLOAD;
$priceAttributes = FHtml::FIELDS_PRICE;
$dateAttributes = FHtml::FIELDS_DATE;
$commonAttributes = FHtml::FIELDS_COMMON;
$imageAttributes = FHtml::FIELDS_IMAGES;
$lookupAttributes = FHtml::FIELDS_LOOKUP;
$booleanAttributes = FHtml::FIELDS_BOOLEAN;
$passwordAttributes = FHtml::FIELDS_PASSWORDS;

$attributeGroups = ['common' => []];
$generatedAttributes = [];
$meta_objects = [];
$related_objects = [];

$hasColumnsUpload = false;
$fieldsUploads = [];

$allowedAttributes = FHtml::FIELDS_VISIBLE;

// Split all fields into groups based on first word
foreach ($generator->getTableSchema()->columns as $column) {
    $attribute = $column->name;

    if (!in_array($attribute, $allowedAttributes))
        continue;

    $commentArray = FHtml::toArrayFromDbComment($column->comment);

    if (isset($commentArray['related']))
        $related_objects = array_merge($related_objects, explode(',', Helper::prepareStringForExplode($commentArray['related'])));

    if (isset($commentArray['meta']))
        $meta_objects = array_merge($meta_objects, explode(',', Helper::prepareStringForExplode($commentArray['meta'])));

    $editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';

    $group = isset($commentArray['group']) ? $commentArray['group'] : (strpos($attribute, '_') > 0 ? substr($attribute, 0, strpos($attribute, '_')) : 'common');

    if (in_array($editor, ['file', 'image', 'upload']) || in_array($group, ['file', 'image', 'upload']) || in_array($attribute, $safeAttributes)  && !FHtml::isInArray($attribute, $hiddenAttributes) && (FHtml::isInArray($attribute, $uploadAttributes) || FHtml::isInArray($attribute, $imageAttributes))) {
        $fieldsUploads[] = $attribute;
        $hasColumnsUpload = true;
    }

    if (FHtml::isInArray($attribute, $countAttributes))
    {
        $group = 'count';
    }

    if (!ArrayHelper::keyExists($group, $attributeGroups))
    {
        $attributeGroups = ArrayHelper::merge($attributeGroups, [$group => [$attribute]]);
    } else {
        $attributeGroups[$group][]= $attribute;
    }
}

//each group has only one member -> merge with common group
foreach ($attributeGroups as $group => $fields) {
    if ($group != 'common') {
        if (count($fields) == 1) {
            $attributeGroups['common'] = ArrayHelper::merge($attributeGroups['common'], $fields);
            ArrayHelper::remove($attributeGroups, $group);
        } else if (count($fields) == 0) {
            ArrayHelper::remove($attributeGroups, $group);
        }
    }
}

$count = 0;
foreach ($attributeGroups['common'] as $field) {
    if (!FHtml::isInArray($field, FHtml::FIELDS_HIDDEN)) {
        $count += 1;
    }
}

if ($count > 0) {
    foreach ($attributeGroups as $group => $fields) {
        if ($group != 'common') { //first group
            $attributeGroups['common'] = ArrayHelper::merge($attributeGroups['common'], $fields);
            ArrayHelper::remove($attributeGroups, $group);
            break;
        }
    }
}

$hasColumnsPassword = false;
$fieldsPassword = [];
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && (FHtml::isInArray($attribute, $passwordAttributes)) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $fieldsPassword[] = $attribute;
        $hasColumnsPassword = true;
    }
}

//foreach ($attributeGroups as $group => $fields){
//    echo 'Group: ' . $group . ': ';
//    foreach ($fields as $field) {
//        echo $field . ', ';
//    }
//    echo '<br/><br/>';
//}
//
//foreach ($attributeGroups['common'] as $field){
//    echo 'common: ' . $field . ', ';
//
//    echo '<br/><br/>';
//}
//var_dump($attributeGroups);die;

$urlParams = $generator->generateUrlParams();


echo "<?php\n";
?>

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole($model, 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = isset($canDelete) ? $canDelete :FHtml::isInRole($model, 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : '_form_add');

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form common\widgets\FActiveForm */
?>

<?= "<?php" ?> if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
}<?= " ?>\n" ?>
<?= "<?php " ?>if ($ajax) Pjax::begin(['id' => 'crud-datatable'])<?= " ?>\n" ?>
<?= "<?php " ?>$form = FActiveForm::begin([
    'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => $edit_type,
    'display_type' => $display_type,
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);<?= " ?>\n" ?>

    <div class="form">
        <div class="row">
            <div class="col-md-12">

                                            <?= "<?= " ?>FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [<?= "\n" ?>
<?php foreach ($generator->getTableSchema()->columns as $column) {
    $attribute = $column->name;
    $commentArray = FHtml::toArrayFromDbComment($column->comment);
    $group = isset($commentArray['group']) ? $commentArray['group'] : (strpos($attribute, '_') > 0 ? substr($attribute, 0, strpos($attribute, '_')) : 'common');
    if (FHtml::isInArray($attribute, $countAttributes))
    {
        $group = 'count';
    }
    if (in_array($attribute, ['code', 'name', 'title', 'overview', 'description', 'content', 'address', 'phone', 'email', 'link_url'])
    && !$column->isPrimaryKey
    && FHtml::isInArray($attribute, $attributeGroups['common'])
    && !FHtml::isInArray($attribute, $hiddenAttributes)
    && !FHtml::isInArray($attribute, $uploadAttributes)
    && !FHtml::isInArray($attribute, $passwordAttributes)
    && !FHtml::isInArray($attribute, $priceAttributes)
    && !in_array($attribute, $generatedAttributes)
    && !in_array($attribute, $fieldsUploads)
    && !FHtml::isInArray($attribute, $groupAttributes)) {
        $generatedAttributes[] = $attribute; ?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute, true) ; ?>
<?php }
} ?>
<?php foreach ($fieldsUploads as $attribute) {
    $generatedAttributes[] = $attribute; ?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php } ?>
                                            <?= "]]); ?>\n"; ?>
<?php
$hasColumns = false;
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $priceAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $hasColumns = true;
    }
}
if (!$hasColumns) { ?>
                                            <?= "<?php /*\n" ?>;
<?php } else { ?>
                                            <?= "<?= " ?>FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Pricing'), 'form' => $form, 'columns' => 2, 'attributes' => [<?= "\n" ?>
<?php } ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (!$hasColumns)
        continue;
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $priceAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $generatedAttributes[] = $attribute; ?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
<?php
if (!$hasColumns) { ?>
                                            <?= "*/ ?>\n" ?>
<?php } else { ?>
                                            <?= "]]); ?>\n"; ?>
<?php } ?>
<?php
$hasColumns = false;
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $groupAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $hasColumns = true;
    }
}
if (!$hasColumns) { ?>
                                            <?= "<?php /*\n" ?>
<?php  } else { ?>
                                            <?= "<?= " ?>FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 2, 'attributes' => [<?= "\n" ?>
<?php } ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (!$hasColumns)
        continue;
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $groupAttributes) && !FHtml::isInArray($attribute, $booleanAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $generatedAttributes[] = $attribute; ?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (!$hasColumns)
        continue;
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $booleanAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $generatedAttributes[] = $attribute; ?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
<?php
if (!$hasColumns) { ?>
                                            <?= "*/ ?>\n" ?>
<?php } else { ?>
                                            <?= "]]); ?>\n" ?>
<?php } ?>
            </div>
            <div class="col-md-12">
                <?= "<?= " ?>(FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete, $ajax) : FHtml::showActionsButton($model, $canEdit, $canDelete, $ajax)<?= " ?>\n" ?>
            </div>
        </div>
    </div>
<?= "<?php "?>FActiveForm::end();<?= " ?>\n" ?>
<?= "<?php "?>if ($ajax) Pjax::end()<?= " ?>" ?>
