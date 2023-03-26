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

$moduleName = 'SettingsLookup';
$moduleTitle = 'Settings Lookup';
$moduleKey = 'settings-lookup';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsLookup */
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
'id' => 'settings-lookup-form',
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
                            <a href="#tab_1_1<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>" data-toggle="tab"><?= FHtml::t('common', 'Info')?></a>
                        </li>
                                                  <li>
                                <a href="#tab_1_4<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>" data-toggle="tab"><?= FHtml::t('common', 'Is')?></a>
                                </li>
                            <li>
                                <a href="#tab_1_5<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>" data-toggle="tab"><?= FHtml::t('common', 'Sort')?></a>
                                </li>
                            <li>
                                <a href="#tab_1_6<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>" data-toggle="tab"><?= FHtml::t('common', 'Application')?></a>
                                </li>
                            </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                               <?= FHtml::showModelField($model,'name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'params', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'params', 'varchar(2000)', '', '') ?>

       <?= FHtml::showModelField($model,'fields', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'fields', 'varchar(2000)', '', '') ?>

       <?= FHtml::showModelField($model,'orderby', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'orderby', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'limit', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'limit', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'sql', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_lookup', 'sql', 'varchar(2000)', '', '') ?>

                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                <!--<div class="tab-pane row" id="tab_1_2<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                -->                                <!--<div class="tab-pane row" id="tab_1_3<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                    <div class="col-md-12">
                                        <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                    </div>
                                </div>
                                -->                                                                        <div class="tab-pane row" id="tab_1_4<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                            <div class="col-md-12">
                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                       <?= FHtml::showModelField($model,'is_cached', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_lookup', 'is_cached', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_lookup', 'is_active', 'tinyint(4)', '', '') ?>

                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                            </div>
                                        </div>
                                                                                <div class="tab-pane row" id="tab_1_5<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                            <div class="col-md-12">
                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                            </div>
                                        </div>
                                                                                <div class="tab-pane row" id="tab_1_6<?= FHtml::getFieldValue($model, ['id', 'product_id']) ?>">
                                            <div class="col-md-12">
                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                                                                                                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                            </div>
                                        </div>
                                                                    </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
   <?php ActiveForm::end(); ?>




