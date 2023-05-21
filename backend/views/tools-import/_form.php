<?php

/**



 * This is the customized model class for table "ToolsImport".
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'ToolsImport';
$moduleTitle = 'Tools Import';
$moduleKey = 'tools-import';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
//if (!empty($_POST))
//$result = isset($result) ? $result : \common\components\FBackup::loadExcelContent($model, $model->sheet_name);
$result = isset($result) ? $result : [];


/* @var $this yii\web\View */
/* @var $model backend\modules\tools\models\ToolsImport */
/* @var $form yii\widgets\ActiveForm */
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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable-pjax']) ?>

<?php $form = FActiveForm::begin([
    'id' => 'tools-import-form',
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
]);
?>


<div class="form">
    <div class="row">

        <div class="col-md-12">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ? FHtml::showPrintHeader($moduleName) : '' ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>
                            : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?> </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                        </li>
                        <li class="">
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Result') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['label' => 'Import Name', 'value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            'object_type' => ['label' => 'Table', 'value' => $form->fieldNoLabel($model, 'object_type')->selectInput(FHtml::getApplicationObjectTypes())],
                                        ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'title' => 'File', 'open' => true, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'file' => ['open' => true, 'value' => $form->fieldNoLabel($model, 'file')->file()],

                                            'file_type' => ['value' => $form->fieldNoLabel($model, 'file_type')->selectCondition(['excel', 'csv'], [], [
                                                'excel' => 'file_type-excel',
                                                'csv' => 'file_type-csv'
                                            ])],
                                        ]]); ?>
                                    </div>
                                    <div class="col-md-12">

                                        <?php echo FFormTable::widget(['id' => 'file_type-excel', 'model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'sheet_name' => ['value' => $form->fieldNoLabel($model, 'sheet_name')->textInput()],
                                            'first_row' => ['label' => 'Start Row', 'value' => $form->fieldNoLabel($model, 'first_row')->numeric()],
                                            'last_row' => ['label' => 'End Row', 'value' => $form->fieldNoLabel($model, 'last_row')->numeric()],

                                        ]]); ?>
                                        <?php echo FFormTable::widget(['id' => 'file_type-csv', 'model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'item_seperator' => ['value' => $form->fieldNoLabel($model, 'item_seperator')->selectInput(["," => "Comma (,)", '|' => "Vertical Bar (|)"])],
                                        ]]); ?>

                                    </div>

                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['id' => 'database', 'model' => $model, 'title' => FHtml::t('common', 'Database'), 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'columns' => ['label' => 'Mapping Fields', 'value' => $form->fieldNoLabel($model, 'columns')->multipleInput(\backend\models\ToolsImport::FIELD_COLUMNS_KEYS)],
                                            'key_columns' => ['label' => 'Check Unique Columns', 'value' => $form->fieldNoLabel($model, 'key_columns')->multipleInput()],
                                            'override_type' => ['label' => 'If Row existed', 'value' => $form->fieldNoLabel($model, 'override_type')->select(FHtml::getComboArray('tools_import', 'tools_import', 'override_type', true, 'id', 'name'))],
                                            'default_values' => ['value' => $form->fieldNoLabel($model, 'default_values')->multipleInput(\backend\models\ToolsImport::FIELD_DEFAULT_VALUES_KEYS)],
                                        ]]); ?>

                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?php if (!empty($result)) { ?>
                                            <?= FHtml::showArrayAsTable($result['data'], $result['excel_columns']) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
            ?>
            <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>


        </div>

    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>