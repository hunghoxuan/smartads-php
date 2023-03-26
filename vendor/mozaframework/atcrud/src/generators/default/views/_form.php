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

// Split all fields into groups based on first word
foreach ($generator->getTableSchema()->columns as $column) {
    $attribute = $column->name;
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
        continue;
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

$urlParams = $generator->generateUrlParams();
?>
<?= "<?php\n"; ?>

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];
$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';

$role = isset($role) ? $role : FHtml::getCurrentRole();
$action = isset($action) ? $action : FHtml::currentAction();

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole($model, 'edit', $role, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = isset($canDelete) ? $canDelete :FHtml::isInRole($model, 'delete', $role);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($action) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($action) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type = isset($view_type) ? $view_type : FHtml::getRequestParam(['form_width', 'layout']);

if ($model->isNewRecord || $view_type == 'full' || $view_type == 'no' || Yii::$app->request->isAjax) {
    $col_size1 = 12;
    $col_size2 = 0;
} else {
    $col_size1 = 9;
    $col_size2 = 3;
}
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($action) ? false : true);

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
<?= "<?php " ?>if ($ajax) {
    Pjax::begin(['id' => 'crud-datatable']);
}<?= " ?>\n"  ?>
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
        'enctype' => 'multipart/form-data'
    ]
]);
<?= "?>\n" ?>
    <div class="form">
        <div class="row">
            <div class="col-md-<?= "<?= " ?>$col_size1<?= " ?>" ?>">
                <div class="portlet light">
                    <div class="visible-print">
                        <?= "<?= " ?>FHtml::isViewAction($action) ? FHtml::showPrintHeader($moduleName) : ''<?= " ?>\n" ?>
                    </div>
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= "<?= " ?>FHtml::t('common', $moduleTitle) . ":" . FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME)<?= " ?>" ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= "<?= " ?>FHtml::t('common', 'Info')<?= " ?>" ?></a>
                            </li>
                            <!--

                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= "<?= " ?>FHtml::t('common', 'Files')<?= " ?>" ?></a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"><?= "<?= " ?>FHtml::t('common', 'Attributes')<?= " ?>" ?></a>
                            </li>
                            -->
<?php
$i = 4;
foreach ($related_objects as $object) { ?>
                            <li>
                                <a href="#tab_1_<?=$i?>" data-toggle="tab"><?= "<?= " ?>FHtml::t('common', '<?= \yii\helpers\BaseInflector::camelize($object) ?>')<?= " ?>" ?></a>
                            </li>
<?php $i += 1; } ?>
<?php if ($hasColumnsPassword) { ?>
                            <li>
                                <a href="#tab_1_p" data-toggle="tab"><?= "<?= " ?>FHtml::t('common', 'Password')<?= " ?>" ?></a>
                            </li>
<?php }?>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                            <?= "<?= "?>FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [<?= "\n" ?>
<?php
foreach ($generator->getTableSchema()->columns as $column) {
$attribute = $column->name;
$commentArray = FHtml::toArrayFromDbComment($column->comment);
$group = isset($commentArray['group']) ? $commentArray['group'] : (strpos($attribute, '_') > 0 ? substr($attribute, 0, strpos($attribute, '_')) : 'common');
if (FHtml::isInArray($attribute, $countAttributes))
{
    $group = 'count';
}
if (in_array($attribute, $safeAttributes)
&& !$column->isPrimaryKey
&& FHtml::isInArray($attribute, $attributeGroups['common'])
&& !FHtml::isInArray($attribute, $hiddenAttributes)
&& !FHtml::isInArray($attribute, $uploadAttributes)
&& !FHtml::isInArray($attribute, $passwordAttributes)
&& !FHtml::isInArray($attribute, $priceAttributes)
&& !in_array($attribute, $generatedAttributes)
&& !in_array($attribute, $fieldsUploads)
&& !FHtml::isInArray($attribute, $groupAttributes)) {
$generatedAttributes[] = $attribute;
?>
                                                <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
                                            <?= "]]); ?>\n"; ?>

<?php
$hasColumns = false;
foreach ($generator->getColumnNames() as $attribute) {
if (in_array($attribute, $safeAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $priceAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
    $hasColumns = true;
    }
}
?>
<?php if (!$hasColumns) { ?>
                                            <?= "<?php /*\n"; ?>
<?php } ?>
                                            <?="<?= "?>FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Pricing'), 'form' => $form, 'columns' => 2, 'attributes' => [<?= "\n"; ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
if (!$hasColumns)
            continue;
if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $priceAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
$generatedAttributes[] = $attribute;
?>
                                                <?= $generator->generateActiveFieldNoLabel($attribute); ?>
<?php }
    }
?>
                                            <?= "]]); ?>\n"; ?>
<?php if (!$hasColumns) { ?>
                                            <?= " */ ?>\n"; ?>
<?php }
                                            $hasColumns = false;
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $groupAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
        $hasColumns = true;
    }
}
if (!$hasColumns) { ?>
                                            <?= "<?php /*\n"; ?>
<?php } else  ?>
                                            <?="<?= "?>FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Group'), 'form' => $form, 'columns' => 2, 'attributes' => [<?="\n"?>
<?php foreach ($generator->getColumnNames() as $attribute) {
if (!$hasColumns)
    continue;
if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $groupAttributes) && !FHtml::isInArray($attribute, $booleanAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
    $generatedAttributes[] = $attribute; ?>
                                                <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
if (!$hasColumns)
    continue;
if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes) && FHtml::isInArray($attribute, $booleanAttributes) && FHtml::isInArray($attribute, $attributeGroups['common'])) {
$generatedAttributes[] = $attribute; ?>
                                                <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php }
} ?>
<?php
if (!$hasColumns) { ?>
                                            <?= " */ ?>\n"; ?>
<?php } else ?>
                                            <?= "]]); ?>\n"; ?>
<?php
foreach ($attributeGroups as $group => $fields) {
    $count = 0;
    foreach ($fields as $attribute) {
        if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes)) {
            $count += 1;
        }
    }
if ($group == 'common' || empty($fields) || $count == 0)
continue;
?>
                                            <?="<?= "?>FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', '<?= $group ?>'), 'form' => $form, 'columns' => 2, 'attributes' => [<?= "\n" ?>
<?php foreach ($fields as $attribute) {
    if (in_array($attribute, $safeAttributes) && !in_array($attribute, $generatedAttributes) && !FHtml::isInArray($attribute, $hiddenAttributes)) {
        $generatedAttributes[] = $attribute; ?>
                                                <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php  }
} ?>
                                            <?= "]]); ?>\n"; ?>
<?php } ?>

                                            <?="<?= "?>FFormTable::widget(['model' => $model, 'title' => 'Images', 'form' => $form, 'columns' => 1, 'attributes' => [<?= "\n" ?>
                                            <?php foreach ($fieldsUploads as $attribute) {
                                                $generatedAttributes[] = $attribute; ?>
                                                <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
                                            <?php } ?>
                                            <?= "]]); ?>\n"; ?>

                                        </div>
                                    </div>
                                    <!--
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                            <?="<? /*= "?>FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [<?= "\n" ?>
                                            '_files' => ['value' => $form->fieldNoLabel($model, 'ObjectFile')->multipleFiles(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            <?= "]]); */ ?>\n"; ?>
                                        </div>
                                    </div>
                                    -->
                                    <!--
                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <?="<? /*= "?>FormObjectAttributes::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]); */<?= " ?>\n" ?>
                                        </div>
                                    </div>
                                    -->
<?php
$i = 4;
foreach ($related_objects as $object) {
    $object_field_name = \yii\helpers\BaseInflector::camelize(str_replace('\\', '_', $object));
    $arr = explode('\\', $object);
    if (count($arr) > 1)
    {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else
    {
        $object_type = $object;
        $object_relation = '';
    }
?>
                                    <div class="tab-pane row" id="tab_1_<?= $i ?>">
                                        <div class="col-md-12">
                                            <?="<?= "?>FormRelations::widget([
                                                'model' => $model,
                                                'form' => $form,
                                                'field_name' => '<?= $object_field_name ?>',
                                                'object_type' => '<?= $object_type?>',
                                                'relation_type' => '<?= $object_relation ?>',
                                                'canEdit' => $canEdit,
                                                'moduleKey' => $moduleKey,
                                                'modulePath' => $modulePath
                                            ])<?= " ?>\n" ?>
                                        </div>
                                    </div>
<?php $i += 1; } ?>
<?php if (!$hasColumnsPassword) { ?>
                                    <?= "<?php /*\n"; ?>
<?php } ?>
                                    <div class="tab-pane row" id="tab_1_p">
                                        <div class="col-md-12">
<?php foreach ($fieldsPassword as $attribute) {
$generatedAttributes[] = $attribute; ?>
                                            <?=  $generator->generateActiveFieldNoLabel($attribute) ; ?>
<?php } ?>
                                        </div>
                                    </div>
<?php
if (!$hasColumnsPassword) { ?>
                                    <?= " */ ?>\n"; ?>
<?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
foreach ($meta_objects as $object) { ?>
                <?= "<?php\n" ?>
                $type = FHtml::getFieldValue($model, '<?= $object ?>');
                if (!empty($type) && in_array($type, ['type1'])) { ?>
                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?="<?= " ?>FHtml::t('common', $type)<?= " ?>" ?></span>
                            </div>
                            <div class="tools pull-right">
                                <a href="#" class="fullscreen"></a>
                                <a href="#" class="collapse"></a>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?="<?= " ?>$this->render('../'. $moduleKey . '-'. $type .'/_fields', ['model' => $modelMeta, 'form_Type' => $this->params['activeForm_type'], 'canEdit' => $canEdit  ])<?= " ?>\n" ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?= "<?php } ?>\n" ?>
<?php } ?>
                <?= "<?php " ?>$type = FHtml::getFieldValue($model, 'type');
                if (isset($modelMeta) && !empty($type)) { ?>
                    <?="<?= " ?>FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);<?= " ?>\n" ?>
                <?= "<?php } ?>\n" ?>
                <?= "<?= " ?>FHtml::isViewAction($action) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete)<?=" ?>\n" ?>
            </div>
            <?= "<?php if (\$col_size2 > 0) { ?>\n" ?>
                <div class="profile-sidebar col-md-<?= "<?= " ?>$col_size2<?= " ?>" ?> col-xs-12 hidden-print">
                    <div class="portlet light">
                        <?= "<?=" ?> FHtml::showModelPreview($model)<?= " ?>\n" ?>
                    </div>
                </div>
            <?= "<?php } ?>\n" ?>
        </div>
    </div>
<?= "<?php " ?>FActiveForm::end();<?= " ?>\n" ?>
<?= "<?php " ?>if ($ajax) Pjax::end();<?= " ?>" ?>