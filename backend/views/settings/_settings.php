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
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';


$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$modules = FHtml::getApplicationModulesComboArray();
$result = [];
$canEdit = true;
foreach ($modules as $module => $name) {
    $file = FHtml::getRootFolder() . "/backend/modules/$module/views/settings/_settings.php";
    if (!is_file($file))
        unset($modules[$module]);
}

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
    //'object_type' => $moduleKey,
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

<?php

FHtml::showCurrentMessages();

?>

<div class="form">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption-title font-blue-madison bold uppercase">
                        <div class="row"><div class="col-md-12 caption-title font-blue-madison bold uppercase"><h3 style="margin-top:-5px"> <?= FHtml::t('common', $moduleTitle) ?></h3></div>
                        </div>
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
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Contact') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Backend') ?></a>
                        </li>
                        <!--
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Languagues') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_7" data-toggle="tab"><?= FHtml::t('common', 'System') ?></a>
                        </li>

                        <li>
                            <a href="#tab_1_5" data-toggle="tab"><?= FHtml::t('common', 'Website') ?></a>

                        </li>
                        <li>
                            <a href="#tab_1_8" data-toggle="tab"><?= FHtml::t('common', 'SEO') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_6" data-toggle="tab"><?= FHtml::t('common', 'API') ?></a>
                        </li>
                        -->
                        <?php foreach ($modules as $module => $name) { ?>
                        <li>
                            <a href="#tab_<?= $module ?>" data-toggle="tab"><?= $name ?></a>
                        </li>
                        <?php } ?>

                    </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => '',
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'name' => [
                                                    'value'         => $form->fieldNoLabel($model, 'name')
                                                        ->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'description'   => [
                                                    'value'         => $form->fieldNoLabel($model, 'description')->textarea(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'slogan'   => [
                                                    'value'         => $form->fieldNoLabel($model, 'slogan')->textarea(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'copyright'   => [
                                                    'value'         => $form->fieldNoLabel($model, 'copyright')->textarea(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'bottom_running_text'   => [
                                                    'value'         => $form->fieldNoLabel($model, 'bottom_running_text')->textarea(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                        ]]); ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [

                                                'logo' => [
                                                    'value'         => $form->fieldNoLabel($model, 'logo')
                                                            ->image() . '<br/>&nbsp;' . FHtml::showCurrentLogo(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'favicon' => [
                                                    'value'         => $form->fieldNoLabel($model, 'favicon')
                                                            ->image() . '<br/>&nbsp;' . FHtml::showImage($model->favicon, 'www', '50px', '50px'),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'main_color'  => $form->fieldNoLabel($model, 'main_color')->color(),

                                            ]]); ?>


                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [

                                                'phone' => [
                                                    'value'         => $form->fieldNoLabel($model, 'phone')
                                                        ->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'email' => [
                                                    'value'         => $form->fieldNoLabel($model, 'email')
                                                        ->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'website' => [
                                                    'value'         => $form->fieldNoLabel($model, 'website')
                                                        ->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'facebook' => [
                                                    'value'         => $form->fieldNoLabel($model, 'facebook')
                                                        ->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'skype',
                                                'address'   => [
                                                    'value'         => $form->fieldNoLabel($model, 'address')->textarea(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                            ]]); ?>
                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'languages_enabled'      => [
                                                    'value'         => $form->fieldNoLabel($model, 'languages_enabled')->checkbox(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'lang'      => $form->fieldNoLabel($model, 'lang')->select(FHtml::getLanguagesArray()),

                                                'languages'      => [
                                                    'value'         => $form->fieldNoLabel($model, 'languages')->checkboxList(FHtml::getLanguagesArray()),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'db_languages_enabled'      => $form->fieldNoLabel($model, 'db_languages_enabled')->checkbox(),

                                                'languages_auto_saved'      => ['value' => $form->fieldNoLabel($model, 'languages_auto_saved')->checkbox(), 'required' => true],

                                            ]]); ?>
                                        <?php if (FHtml::isRoleAdmin()) { ?>

                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_4">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Themes'),
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'hide_field' => false,
                                            'open' => true,
                                            'attributes' => [
                                                'theme_color'      => $form->fieldNoLabel($model, 'theme_color')->select(FHtml::ARRAY_ADMIN_THEME),
                                                'theme_style'      => $form->fieldNoLabel($model, 'theme_style')->select(FHtml::ARRAY_THEME_STYLE),
                                                'portlet_style'      => [
                                                    'value'         => $form->fieldNoLabel($model, 'portlet_style')->select(FHtml::ARRAY_PORTLET_STYLE),
                                                ],
                                                'form_control_border'      => $form->fieldNoLabel($model, 'form_control_border')->select(FHtml::ARRAY_FORM_CONTROL_STYLE),
                                                'backend_main_color'      => $form->fieldNoLabel($model, 'backend_main_color')->color(),
                                                'backend_font_size'      => $form->fieldNoLabel($model, 'backend_font_size')->select(FHtml::ARRAY_FONT_SIZE),
                                            ]]); ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Login'),
                                            'hide_field' => false,
                                            'open' => true,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'backend_login_position' => $form->fieldNoLabel($model, 'backend_login_position')->select(FHtml::ARRAY_ALIGNMENT),
                                                'backend_background' => [
                                                    'value'         => $form->fieldNoLabel($model, 'backend_background')
                                                            ->image() . '<br/>&nbsp;' . FHtml::showImage($model->backend_background, 'www', '', '200px'),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]]); ?>
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'List View'),
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'border_style'      => [
                                                    'value'         => $form->fieldNoLabel($model, 'border_style')->select(FHtml::ARRAY_BORDER_STYLE),
                                                ],
                                                'table_border_color'      => $form->fieldNoLabel($model, 'table_border_color')->color(),
                                                'table_border_width'      => $form->fieldNoLabel($model, 'table_border_width')->select(FHtml::ARRAY_BORDER_WIDTH),
                                                'table_header_color'      => $form->fieldNoLabel($model, 'table_header_color')->color(),
                                                'table_strip_light_color'      => $form->fieldNoLabel($model, 'table_strip_light_color')->color(),
                                                'table_strip_dark_color'      => $form->fieldNoLabel($model, 'table_strip_dark_color')->color(),
//                                                'show_preview_column'      => [
//                                                    'value'         => $form->fieldNoLabel($model, 'show_preview_column')->checkbox(),
//                                                ],
                                                'admin_inline_edit'      => $form->fieldNoLabel($model, 'admin_inline_edit')->checkbox(),
                                                'admin_grid_show_views'      => $form->fieldNoLabel($model, 'admin_grid_show_views')->checkbox(),

                                            ]]); ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Detail View'),
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'controls_alignment'      => [
                                                    'value'         => $form->fieldNoLabel($model, 'controls_alignment')->select(FHtml::ARRAY_CONTROLS_ALIGNMENT),
                                                ],

                                                'form_width'      => $form->fieldNoLabel($model, 'form_width')->select(FHtml::ARRAY_FORM_WIDTH_TYPE),
                                                'form_background'      => $form->fieldNoLabel($model, 'form_background')->color(),

                                                    'form_label_color'      => $form->fieldNoLabel($model, 'form_label_color')->color(),
                                                'form_label_spacing'      => $form->fieldNoLabel($model, 'form_label_spacing')->checkbox(),
                                                'form_label_border'      => $form->fieldNoLabel($model, 'form_label_border')->select(FHtml::ARRAY_BORDER_TYPE),

                                                'form_control_color'      => $form->fieldNoLabel($model, 'form_control_color')->color(),
                                                'form_control_height'      => $form->fieldNoLabel($model, 'form_control_height')->textbox(),
                                                'form_buttons_style'      => $form->fieldNoLabel($model, 'form_buttons_style')->select(FHtml::ARRAY_BUTTONS_POSITION_TYPE),
                                                'thumbnail_size'      => $form->fieldNoLabel($model, 'thumbnail_size')->numeric(),

                                            ]]); ?>


                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Others'),
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'backend_header_color'      => $form->fieldNoLabel($model, 'backend_header_color')->color(),
                                                'backend_header_background'      => $form->fieldNoLabel($model, 'backend_header_background')->color(),
                                                'backend_menu_active_color'      => $form->fieldNoLabel($model, 'backend_menu_active_color')->color(),
                                                'backend_menu_background_color'      => $form->fieldNoLabel($model, 'backend_menu_background_color')->color(),
                                                'backend_menu_style'      => $form->fieldNoLabel($model, 'backend_menu_style')->select(FHtml::ARRAY_MENU_STYLE),

                                                'backend_background_color'      => $form->fieldNoLabel($model, 'backend_background_color')->color(),
                                                'backend_footer_style'      => $form->fieldNoLabel($model, 'backend_footer_style')->select(FHtml::ARRAY_FOOTER_STYLE),


                                            ]]); ?>


                                        <?php if (FHtml::isRoleAdmin()) { ?>

                                        <?php } ?>
                                    </div>

                                </div>

                                <div class="tab-pane row" id="tab_1_5">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                //'frontend_framework'  => $form->fieldNoLabel($model, 'frontend_framework')->select(['php', 'wordpress']),
                                                //'frontend_theme'  => $form->fieldNoLabel($model, 'frontend_theme')->select(FHtml::getFrontendThemesArray()),
                                                'main_color'  => $form->fieldNoLabel($model, 'main_color')->color(),

                                                'cart_enabled'  => $form->fieldNoLabel($model, 'cart_enabled')->checkbox(),
                                                'fonts'  => $form->fieldNoLabel($model, 'fonts')->textInput(),
                                                'page_width' => $form->fieldNoLabel($model, 'page_width')->textInput(),
                                            ]]); ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Chat'),
                                            'form'       => $form,
                                            'columns'    => 3,
                                            'attributes' => [
                                                'show_as_footer'      => $form->fieldNoLabel($model, 'show_as_footer')->checkbox(),
                                                'show_as_buttons'      => $form->fieldNoLabel($model, 'show_as_buttons')->checkbox(),
                                            ]]); ?>
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'LiveZilla Chat',
                                            'form'       => $form,
                                            'columns'    => 3,
                                            'attributes' => [
                                                'livezilla_chat_enabled'      => $form->fieldNoLabel($model, 'livezilla_chat_enabled')->checkbox(),
                                                'livezilla_chat_url'      => $form->fieldNoLabel($model, 'livezilla_chat_url')->textbox(),
                                                'livezilla_chat_id'      => $form->fieldNoLabel($model, 'livezilla_chat_id')->textbox(),
                                            ]]); ?>
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'Tidio Chat',
                                            'form'       => $form,
                                            'columns'    => 3,
                                            'attributes' => [
                                                'tidio_chat_enabled'      => $form->fieldNoLabel($model, 'tidio_chat_enabled')->checkbox(),
                                                'tidio_chat_url'      => $form->fieldNoLabel($model, 'tidio_chat_url')->textbox(),
                                            ]]); ?>
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'Facebook Chat',
                                            'form'       => $form,
                                            'columns'    => 3,
                                            'attributes' => [
                                                'facebook_chat_enabled'      => $form->fieldNoLabel($model, 'facebook_chat_enabled')->checkbox(),
                                                'facebook_page_id'      => $form->fieldNoLabel($model, 'facebook_page_id')->textbox(),
                                            ]]); ?>
                                        <?php if (FHtml::isRoleAdmin()) { ?>

                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_8">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'keywords'  => $form->fieldNoLabel($model, 'keywords')->textarea(),
//                                                'description'   => [
//                                                    'value'         => $form->fieldNoLabel($model, 'description')->textarea(),
//                                                    'columnOptions' => ['colspan' => 1],
//                                                    'type'          => FHtml::INPUT_RAW
//                                                ],
                                            ]]); ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'form'       => $form,
                                            'title'      => 'Scripts & Stylesheets',
                                            'columns'    => 1,
                                            'attributes' => [
                                                'google_verification'  => $form->fieldNoLabel($model, 'google_verification')->textbox(),
                                                'google_analytic_key'  => $form->fieldNoLabel($model, 'google_analytic_key')->textbox(),
                                                'google_adwords_key'  => $form->fieldNoLabel($model, 'google_adwords_key')->textbox(),
                                                'website_scripts'  => $form->fieldNoLabel($model, 'website_scripts')->textarea(['rows' => 10]),
                                                'website_stylesheets'  => $form->fieldNoLabel($model, 'website_stylesheets')->textarea(['rows' => 10]),

                                            ]]); ?>
                                        <?php if (FHtml::isRoleAdmin()) { ?>

                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_6">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => false,
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'api.ipaddress.allowed'  => $form->fieldNoLabel($model, 'api_ipaddress_allowed')->textarea(),
                                                'api.ipaddress.blocked'  => $form->fieldNoLabel($model, 'api_ipaddress_blocked')->textarea(),
                                                'api.token.allowed'  => $form->fieldNoLabel($model, 'api_token_allowed')->textarea(),
                                                'default_username'  => $form->fieldNoLabel($model, 'api_default_username')->textInput(),

                                            ]]); ?>
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'GOOGLE KEYS',
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'GOOGLE_API_KEY'  => $form->fieldNoLabel($model, 'GOOGLE_API_KEY')->textarea(),
                                                'google_map_api_key'  => $form->fieldNoLabel($model, 'google_map_api_key')->textarea(),

                                            ]]); ?>

                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_7">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => FHtml::t('common', 'Format'),
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'timezone'  => $form->fieldNoLabel($model, 'timezone')->select(FHtml::getTimeZoneArray()),
                                                'locale'  => $form->fieldNoLabel($model, 'locale')->textInput(),

                                                'format_date'  => $form->fieldNoLabel($model, 'format_date')->textInput()->hint('Y: Year, m: month, d: date. Example: d.m.Y; d-m-Y; Y-m-d; D j/n/Y'),
                                                'format_datetime'  => $form->fieldNoLabel($model, 'format_datetime')->textInput()->hint('Y: Year, m: month, d: date, H: hour, m: minute, s: second'),
                                                'time_format'  => $form->fieldNoLabel($model, 'time_format')->textInput()->hint('H: hour, m: minute, s: second'),
                                                'default_currency'  => $form->fieldNoLabel($model, 'default_currency')->select(FHtml::getCurrencyArray()),
                                                'display_decimals'  => $form->fieldNoLabel($model, 'display_decimals')->numeric(),
                                                'decimal_point'  => $form->fieldNoLabel($model, 'decimal_point')->select(['' => '', '.' => '. (dot)', ',' => ', (comma)']),
                                                'thousands_sep'  => $form->fieldNoLabel($model, 'thousands_sep')->select(['' => '', '.' => '. (dot)', ',' => ', (comma)']),

                                                'page_size'  => $form->fieldNoLabel($model, 'page_size')->numeric(),

                                            ]]); ?>
                                        <?php if (FHtml::isRoleAdmin()) { ?>
                                            <?= \common\widgets\FFormTable::widget([
                                                'model'      => $model,
                                                'title'      => FHtml::t('common', 'Files'),
                                                'form'       => $form,
                                                'columns'    => 1,
                                                'attributes' => [
                                                    'upload_accepted_type'  => $form->fieldNoLabel($model, 'upload_accepted_type')->textarea(),
                                                    'upload_max_size'  => $form->fieldNoLabel($model, 'upload_max_size')->numeric(),

                                                ]]); ?>
                                            <?= \common\widgets\FFormTable::widget([
                                                'model'      => $model,
                                                'title'      => FHtml::t('common', 'System'),
                                                'form'       => $form,
                                                'columns'    => 2,
                                                'attributes' => [
                                                    'show_error'      => $form->fieldNoLabel($model, 'show_error')->checkbox(),

                                                    'db_settings_enabled'      => $form->fieldNoLabel($model, 'db_settings_enabled')->checkbox(),
                                                    'db_security_enabled'      => $form->fieldNoLabel($model, 'db_security_enabled')->checkbox(),
                                                    'dynamic_object_enabled'      => $form->fieldNoLabel($model, 'dynamic_object_enabled')->checkbox(),
                                                    'settings_query_enabled'      => $form->fieldNoLabel($model, 'settings_query_enabled')->checkbox(),
                                                    'dynamic_form_enabled'      => $form->fieldNoLabel($model, 'dynamic_form_enabled')->checkbox(),

                                                    'widgets_enabled'      => $form->fieldNoLabel($model, 'widgets_enabled')->checkbox(),

                                                ]]); ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php foreach ($modules as $module => $name) { ?>

                                <div class="tab-pane row" id="tab_<?= $module ?>">
                                    <div class="col-md-12">
                                        <?= FHtml::renderFile( FHtml::getRootFolder() . "/backend/modules/$module/views/settings/_settings.php", ['model' => $model, 'form' => $form]) ?>
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <script language="javascript" type="text/javascript">
                function submitForm($saveType) {
                    $('#saveType').val($saveType);
                }
            </script>

            <?php if (Yii::$app->request->isAjax) { ?>

                <input type="hidden" id="saveType" name="saveType">

            <?php } else { ?>
                <input type="hidden" id="saveType" name="saveType">

                <div class="hidden-print form-label" style="padding:15px; padding-bottom:0px; right:0px; left:0px; position: fixed; height: auto;bottom: 0;width: 100%; border-top:1px dashed lightgrey; z-index:2;">
                    <?php if ($canEdit) {
                        echo Html::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                    } ?>

                    <?= ' | ' . FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), FHtml::createUrl('/'), ['class' => 'btn btn-default']) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php \common\widgets\FActiveForm::end(); ?>




