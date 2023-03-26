<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'MediaFile';
$moduleTitle = 'Media File';
$moduleKey = 'media-file';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole($moduleName, 'update', $currentRole);
$canDelete = FHtml::isInRole($moduleName, 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\modules\media\models\MediaFile */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
$this->title = FHtml::t($moduleTitle);
$this->params['mainIcon'] = 'fa fa-list';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
} ?>


<?php $form = ActiveForm::begin([
'id' => 'media-file-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => !$canEdit, // check the Role here
'options' => [
//'class' => 'form-horizontal',
'enctype' => 'multipart/form-data'
]
]);
 ?>


<div class="form">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="portlet light">
                <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => FHtml::getFieldValue($model, ['name', 'title'])]) ?>                                <h3><?= FHtml::t('common', 'Common') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'media_file', 'is_active', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'id', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'media_file', 'id', 'bigint(20)', '', '') ?>

       <?= FHtml::showModelField($model,'name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'image', FHtml::SHOW_IMAGE, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'file', FHtml::SHOW_FILE, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'description', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'media_file') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'File') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'file_path', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'file_type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'file_size', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'media_file') ?>

       <?= FHtml::showModelField($model,'file_duration', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'media_file') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                            </div>

        <?php if (Yii::$app->request->isAjax) { ?>

        <input type="hidden" id="saveType" name="saveType">

        <?php } else { ?>
        <p class="hidden-print">
            <a class="btn blue hidden-print " onclick="javascript:window.print();"> Print
                <i class="fa fa-print"></i>
            </a>
            <?php if (FHtml::isInRole($moduleName, 'update', $currentRole)) { Html::a('<i class="fa fa-pencil"></i> ' .  FHtml::t('common', 'button.update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); } ?>
            <?php if (FHtml::isInRole($moduleName, 'delete', $currentRole)) {Html::a('<i class="fa fa-trash"></i> ' .  FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
            'confirm' => FHtml::t('common', 'message.confirmdelete'),
            'method' => 'post',
            ],
            ]);} ?>
            <?=  Html::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default']) ?>

        </p>
        <?php } ?>        </div>
    </div>
</div>
   <?php ActiveForm::end(); ?>




