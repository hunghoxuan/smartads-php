<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
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
$result = isset($result) ? $result : \common\components\FBackup::loadExcelContent($model, $model->sheet_name);

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

        <div class="col-md-9">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ? FHtml::showPrintHeader($moduleName) : '' ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>
                            : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?>                        </span>
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
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Excel Content') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Excel'), 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'file' => ['value' => $form->fieldNoLabel($model, 'file')->file(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'sheet_name' => ['value' => $form->fieldNoLabel($model, 'sheet_name')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'first_row' => ['value' => $form->fieldNoLabel($model, 'first_row')->numeric(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'last_row' => ['value' => $form->fieldNoLabel($model, 'last_row')->numeric(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'override_type' => ['value' => $form->fieldNoLabel($model, 'override_type')->selectInput(FHtml::getComboArray('tools_import', 'tools_import', 'override_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                        ]]); ?>
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Database'), 'form' => $form, 'columns' => 1, 'attributes' => [
                                             'object_type' => ['label' => 'Table', 'value' => $form->fieldNoLabel($model, 'object_type')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'key_columns' => ['value' => $form->fieldNoLabel($model, 'key_columns')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'columns' => ['value' => $form->fieldNoLabel($model, 'columns')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                            'default_values' => ['value' => $form->fieldNoLabel($model, 'default_values')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
                                        ]]); ?>

                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        Results: <?= count($result) ?> <br/>
                                        <?= FHtml::showArrayAsTable($result) ?>
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
