<?php

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];
$moduleName = 'ObjectFile';
$moduleTitle = 'Object File';
$moduleKey = 'object-file';

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
/* @var $model backend\modules\system\models\ObjectFile */
/* @var $form common\widgets\FActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>
<?php if ($ajax) {
    Pjax::begin(['id' => 'crud-datatable']);
} ?>
<?php $form = FActiveForm::begin([
    'id' => 'object-file-form',
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
?>
    <div class="form">
        <div class="row">
            <div class="col-md-<?= $col_size1 ?>">
                <div class="portlet light">
                    <div class="visible-print">
                        <?= FHtml::isViewAction($action) ? FHtml::showPrintHeader($moduleName) : '' ?>
                    </div>
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) . ":" . FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                            </li>
                            <!--

                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Files') ?></a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes') ?></a>
                            </li>
                            -->
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                            <?= FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                                'object_id' => ['value' => $form->fieldNoLabel($model, 'object_id')->select(FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); ?>

                                            <?php /*
                                            <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Pricing'), 'form' => $form, 'columns' => 2, 'attributes' => [
                                            ]]); ?>
                                             */ ?>
                                            <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Group'), 'form' => $form, 'columns' => 2, 'attributes' => [
                                                'object_type' => ['value' => $form->fieldNoLabel($model, 'object_type')->select(FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); ?>
                                            <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'file'), 'form' => $form, 'columns' => 2, 'attributes' => [
                                                'file_type' => ['value' => $form->fieldNoLabel($model, 'file_type')->select(FHtml::getComboArray('object_file', 'object_file', 'file_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'file_size' => ['value' => $form->fieldNoLabel($model, 'file_size')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'file_duration' => ['value' => $form->fieldNoLabel($model, 'file_duration')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); ?>

                                            <?= FFormTable::widget(['model' => $model, 'title' => 'Images', 'form' => $form, 'columns' => 1, 'attributes' => [
                                                                                            'file' => ['value' => $form->fieldNoLabel($model, 'file')->file(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                                                            'file_type' => ['value' => $form->fieldNoLabel($model, 'file_type')->select(FHtml::getComboArray('object_file', 'object_file', 'file_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                                                            'file_size' => ['value' => $form->fieldNoLabel($model, 'file_size')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                                                            'file_duration' => ['value' => $form->fieldNoLabel($model, 'file_duration')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                                                        ]]); ?>

                                        </div>
                                    </div>
                                    <!--
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                            <? /*= FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [
                                            '_files' => ['value' => $form->fieldNoLabel($model, 'ObjectFile')->multipleFiles(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); */ ?>
                                        </div>
                                    </div>
                                    -->
                                    <!--
                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <? /*= FormObjectAttributes::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]); */ ?>
                                        </div>
                                    </div>
                                    -->
                                    <?php /*
                                    <div class="tab-pane row" id="tab_1_p">
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                     */ ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $type = FHtml::getFieldValue($model, 'type');
                if (isset($modelMeta) && !empty($type)) { ?>
                    <?= FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]); ?>
                <?php } ?>
                <?= FHtml::isViewAction($action) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
            </div>
            <?php if ($col_size2 > 0) { ?>
                <div class="profile-sidebar col-md-<?= $col_size2 ?> col-xs-12 hidden-print">
                    <div class="portlet light">
                        <?= FHtml::showModelPreview($model) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end(); ?>