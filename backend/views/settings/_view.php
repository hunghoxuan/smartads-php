<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Settings".
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

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
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


<?php $form = \common\widgets\FActiveForm::begin([
    'id' => 'settings-form',
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
        <?= $this->render('../www/_print_header', ['title' => '',]) ?>
        <div class="profile-sidebar col-md-3 hidden-print">
            <div class="portlet light">
                <div class="margin-top-20">
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
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="row list-separated profile-stat">
                        <div class="col-md-6">
                        </div>
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
                        <span
                            class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
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
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                               <?= FHtml::showModelField($model, 'metaKey', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings', 'metaKey', 'varchar(255)', '', '') ?>

                                        <?= FHtml::showModelField($model, 'metaValue', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings', 'metaValue', 'varchar(255)', '', '') ?>

                                        <?= FHtml::showModelField($model, 'editor', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings', 'editor', 'varchar(255)', '', '') ?>

                                        <?= FHtml::showModelField($model, 'lookup', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings', 'lookup', 'varchar(255)', '', '') ?>

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
                        <span
                            class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Grouping') ?></span>
                    </div>
                </div>
                <div class="">
                    <div class="tab-content">
                        <div class="tab-pane active row" id="tab_1_1">
                            <div class="col-md-12">
                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                       <?= FHtml::showModelField($model, 'is_system', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings', 'is_system', 'tinyint(1)', '', '') ?>

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
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Pricing') ?></span>
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
                    <?php if (FHtml::isInRole('', 'update', $currentRole)) {
                        FHtml::a('<i class="fa fa-pencil"></i> ' . FHtml::t('common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    } ?>
                    <?php if (FHtml::isInRole('', 'delete', $currentRole)) {
                        FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger pull-right',
                            'data' => [
                                'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                'method' => 'post',
                            ],
                        ]);
                    } ?>
                    <?= FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>

                </p>
            <?php } ?>
        </div>
    </div>
</div>
<?php \common\widgets\FActiveForm::end(); ?>




