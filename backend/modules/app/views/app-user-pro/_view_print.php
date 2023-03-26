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

$moduleName = 'AppUserPro';
$moduleTitle = 'App User Pro';
$moduleKey = 'app-user-pro';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserPro */
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


<?php $form = \common\widgets\FActiveForm::begin([
'id' => 'app-user-pro-form',
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
                <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => $model->name]) ?>                                <h3><?= FHtml::t('common', 'Common') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'rate', FHtml::SHOW_CURRENCY, $field_layout, $form_label_CSS, 'app_user_pro', 'rate', 'float(3,1)', '', '') ?>

       <?= FHtml::showModelField($model,'description', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user_pro', 'description', 'varchar(500)', '', '') ?>

       <?= FHtml::showModelField($model,'user_id', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user_pro', 'user_id', 'int(11)', '', '') ?>

       <?= FHtml::showModelField($model,'rate_count', FHtml::SHOW_NUMBER, $field_layout, $form_label_CSS, 'app_user_pro', 'rate_count', 'int(11)', '', '') ?>

       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'app_user_pro', 'is_active', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'business_name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_email', FHtml::SHOW_EMAIL, $field_layout, $form_label_CSS, 'app_user_pro', 'business_email', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_address', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_address', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_website', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_website', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_phone', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user_pro', 'business_phone', 'varchar(20)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                            </div>

        <?php if (Yii::$app->request->isAjax) { ?>

        <input type="hidden" id="saveType" name="saveType">

        <?php } else { ?>
        <p class="hidden-print">
            <a class="btn blue hidden-print " onclick="javascript:window.print();"> Print
                <i class="fa fa-print"></i>
            </a>
            <?php if (FHtml::isInRole('', 'update', $currentRole)) { Html::a('<i class="fa fa-pencil"></i> ' .  FHtml::t('common', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']); } ?>
            <?php if (FHtml::isInRole('', 'delete', $currentRole)) {Html::a('<i class="fa fa-trash"></i> ' .  FHtml::t('common', 'Delete'), ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
            'confirm' => FHtml::t('common', 'Are you sure to delete?'),
            'method' => 'post',
            ],
            ]);} ?>
            <?=  Html::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>

        </p>
        <?php } ?>        </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




