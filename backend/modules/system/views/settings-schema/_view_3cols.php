<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsSchema".
*/

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

$moduleName = 'SettingsSchema';
$moduleTitle = 'Settings Schema';
$moduleKey = 'settings-schema';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsSchema */
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
'id' => 'settings-schema-form',
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
        <?= $this->render(\Globals::VIEWS_PRINT_HEADER1, ['title' => '',]) ?>
        <div class="profile-sidebar col-md-3 hidden-print">
            <div class="portlet light">
                                                                <div class="margin-top-20">
                    <h4><b><?= $model->name  ?></b></h4>
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
                            <?= FHtml::showField(' ', $model->created_user, FHtml::SHOW_USER) ?>                            </div>
                    </div>
                    <div class="row list-separated profile-stat">
                        <div class="col-md-6">
                            <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                            </div>
                        <div class="col-md-6">
                            <?= FHtml::showField(' ', $model->modified_user, FHtml::SHOW_USER) ?>                            </div>
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
                                                </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                               <?= FHtml::showModelField($model,'name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'description', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'description', 'varchar(2000)', '', '') ?>

       <?= FHtml::showModelField($model,'dbType', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_schema', 'dbType', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'editor', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_schema', 'editor', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'lookup', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'lookup', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'format', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'format', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'algorithm', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'algorithm', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'group', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_schema', 'group', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'grid_size', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_schema', 'grid_size', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'roles', FHtml::SHOW_LOOKUP, $field_layout, $form_label_CSS, '@roles', 'roles', 'varchar(500)', '', '') ?>

                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>
                                    </div>
                                </div>
                                <!--<div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>
                                    </div>
                                </div>
                                -->                                <!--<div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>
                                    </div>
                                </div>
                                -->                            </div>
                        </div>

                    </div>
                </div>
            </div>

                        <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Grouping')?></span>
                    </div>
                </div>
                <div class="">
                    <div class="tab-content">
                        <div class="tab-pane active row" id="tab_1_1">
                            <div class="col-md-12">
                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                       <?= FHtml::showModelField($model,'object_type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_schema', 'object_type', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_schema', 'is_active', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'is_system', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_schema', 'is_system', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'is_readonly', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_schema', 'is_readonly', 'tinyint(1)', '', '') ?>

                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Pricing')?></span>
                    </div>
                </div>
                <div class="">
                    <div class="tab-content">
                        <div class="tab-pane active row" id="tab_1_1">
                            <div class="col-md-12">
                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>
                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            -->
            

            <?php if (Yii::$app->request->isAjax) { ?>

            <input type="hidden" id="saveType" name="saveType">

            <?php } else { ?>
            <p class="hidden-print">
                <a class="btn blue hidden-print " onclick="javascript:window.print();"> Print
                    <i class="fa fa-print"></i>
                </a>
                <?php if ($canEdit) { echo Html::a('<i class="fa fa-pencil"></i> ' .  FHtml::t('common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-warning']); } ?>
                <?php if ($canDelete) { echo Html::a('<i class="fa fa-trash"></i> ' .  FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
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




