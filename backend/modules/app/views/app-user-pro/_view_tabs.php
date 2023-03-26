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
        <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['title' => '',]) ?>        <div class="profile-sidebar col-md-3 hidden-print">
            <div class="portlet light">
                                                                <div class="margin-top-20">
                                                            <small class='text-default'><?= $model->description  ?></small>
                </div>
                <div class="margin-top-20">
                                    </div>
                <div class="margin-top-20">
                                    </div>

                <!--
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li class="active">
                            <a href="#">
                                <i class="icon-settings"></i> Edit Detail </a>
                        </li>
                    </ul>
                </div>-->
            </div>
            <!-- END MENU -->
            <div class="portlet light">
                <div class="row list-separated profile-stat">
                    </div>
                <div>
                    <div class="row list-separated profile-stat">
                        <div class="col-md-6">
                            <?= FHtml::showField('Created', FHtml::getFieldValue($model, 'created_date'), FHtml::SHOW_DATE) ?>                            </div>
                        <div class="col-md-6">
                                                        </div>
                    </div>
                    <div class="row list-separated profile-stat">
                        <div class="col-md-6">
                            <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                            </div>
                        <div class="col-md-6">
                                                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info')?></a>
                        </li>
                                                  <li>
                                <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Business')?></a>
                                </li>
                            <li>
                                <a href="#tab_1_5" data-toggle="tab"><?= FHtml::t('common', 'Is')?></a>
                                </li>
                            </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                               <?= FHtml::showModelField($model,'rate', FHtml::SHOW_CURRENCY, $field_layout, $form_label_CSS, 'app_user_pro', 'rate', 'float(3,1)', '', '') ?>

       <?= FHtml::showModelField($model,'description', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user_pro', 'description', 'varchar(500)', '', '') ?>

                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                <!--<div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                -->                                <!--<div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                -->                                                                        <div class="tab-pane row" id="tab_1_4">
                                            <div class="col-md-12">
                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                       <?= FHtml::showModelField($model,'business_name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_email', FHtml::SHOW_EMAIL, $field_layout, $form_label_CSS, 'app_user_pro', 'business_email', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_address', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_address', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_website', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user_pro', 'business_website', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'business_phone', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user_pro', 'business_phone', 'varchar(20)', '', '') ?>

                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                            </div>
                                        </div>
                                                                                <div class="tab-pane row" id="tab_1_5">
                                            <div class="col-md-12">
                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'app_user_pro', 'is_active', 'tinyint(1)', '', '') ?>

                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                            </div>
                                        </div>
                                                                    </div>
                        </div>

                    </div>
                </div>
            </div>



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
            <?php } ?>
        </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




