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
$moduleName = 'AppVersion';
$moduleTitle = 'App Version';
$moduleKey = 'app-version';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type = isset($view_type) ? $view_type : FHtml::getRequestParam('form_width');
if ($view_type == 'full') {
    $col_size1 = 12;
    $col_size2 = 0;
} else {
    $col_size1 = 9;
    $col_size2 = 3;
}
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppVersion */
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
    'id' => 'app-version-form',
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
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'file' => ['value' => $form->fieldNoLabel($model, 'file')->file(['accept' => '']), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'platform' => ['value' => $form->fieldNoLabel($model, 'platform')->select(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'package_version' => ['value' => $form->fieldNoLabel($model, 'package_version')->numeric(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'package_name' => ['value' => $form->fieldNoLabel($model, 'package_name')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'platform_info' => ['value' => $form->fieldNoLabel($model, 'platform_info')->selectMany(\backend\modules\smartscreen\models\SmartscreenStation::findAllForCombo()), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW, 'label' => 'Device'],
                                            //'history' => ['value' => $form->fieldNoLabel($model, 'history')->selectMany(\backend\modules\smartscreen\models\SmartscreenStation::findAllForCombo()), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                            'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'is_default' => ['value' => $form->fieldNoLabel($model, 'is_default')->checkbox(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                        ]]); ?>

                                        <!--
                                        <?= FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Notify Devices'), 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'device' => ['value' => $form->fieldNoLabel($model, '_devices')->selectMany(\backend\modules\smartscreen\models\SmartscreenStation::findAllForCombo(['status' => 1], 'id', 'name', 'name asc')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                            'ime' => ['value' => $form->fieldNoLabel($model, '_ime')->textarea(['rows' => 6]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'Upgrade' => ['value' => $form->fieldNoLabel($model, '_is_upgrade')->checkbox(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                        ]]); ?>
                                        -->
                                    </div>
                                </div>
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