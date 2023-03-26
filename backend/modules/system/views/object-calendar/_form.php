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
$moduleName = 'ObjectCalendar';
$moduleTitle = 'Object Calendar';
$moduleKey = 'object-calendar';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole($model, 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole($model, 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type = isset($view_type) ? $view_type : FHtml::getRequestParam('form_width');
if ($model->isNewRecord || $view_type == 'full' || Yii::$app->request->isAjax) {
    $col_size1 = 12;
    $col_size2 = 0;
} else {
    $col_size1 = 9;
    $col_size2 = 3;
}
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
$ajax = true;
/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectCalendar */
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
    'id' => 'object-calendar-form',
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
                        <?= FHtml::isViewAction($currentAction) ? FHtml::showPrintHeader($moduleName) : '' ?>
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
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads') ?></a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                            <?= FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                                'object_id' => ['value' => $form->fieldNoLabel($model, 'object_id')->select(FHtml::getComboArray('object_calendar', 'object_calendar', 'object_id', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'color' => ['value' => $form->fieldNoLabel($model, 'color')->color(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'title' => ['value' => $form->fieldNoLabel($model, 'title')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'start_date' => ['value' => $form->fieldNoLabel($model, 'start_date')->datetime(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'end_date' => ['value' => $form->fieldNoLabel($model, 'end_date')->datetime(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'all_day' => ['value' => $form->fieldNoLabel($model, 'all_day')->select(FHtml::getComboArray('object_calendar', 'object_calendar', 'all_day', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'link_url' => ['value' => $form->fieldNoLabel($model, 'link_url')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); ?>
                                            <?php /*
                                            <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Pricing'), 'form' => $form, 'columns' => 2, 'attributes' => [
                                            ]]); ?>
                                             */ ?>
                                            <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Group'), 'form' => $form, 'columns' => 2, 'attributes' => [
                                                'object_type' => ['value' => $form->fieldNoLabel($model, 'object_type')->select(FHtml::getComboArray('object_calendar', 'object_calendar', 'object_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'status' => ['value' => $form->fieldNoLabel($model, 'status')->select(FHtml::getComboArray('object_calendar', 'object_calendar', 'status', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                                'type' => ['value' => $form->fieldNoLabel($model, 'type')->select(FHtml::getComboArray('object_calendar', 'object_calendar', 'type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            ]]); ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                            <?= FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [
                                            ]]); ?>
                                            <hr/>
                                            <?= FormObjectFile::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]); ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <?= FormObjectAttributes::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]); ?>
                                        </div>
                                    </div>
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
                <?= FHtml::isViewAction($currentAction) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
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