<?php

/**



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
use backend\modules\smartscreen\models\SmartscreenSchedules;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'smartscreen';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$newForm = isset($form) ? false : true;


//
//FHtml::var_dump(FHtml::getComboArray('ecommerce_product.business'));
//FHtml::var_dump(FHtml::getArrayKeyValues('ecommerce_product.business'));

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

<?php if ($newForm)
    $form = \common\widgets\FActiveForm::begin([
        'id' => 'settings-form',
        //'object_type' => $moduleKey,
        'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
        'staticOnly' => false, // check the Role here
        'readonly' => !$canEdit, // check the Role here
        'enableClientValidation' => false,
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
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'LCD Settings',
                                            'form'       => $form,
                                            'columns'    => 2,
                                            'hide_field' => false,
                                            'open' => true,

                                            'attributes' => [
                                                'smartads.logo_url' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.logo_url')
                                                        ->textInput()->hint("Change logo at devices"),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.logo_opacity' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.logo_opacity')
                                                        ->progress()->hint("0: hide, 100%: show logo."),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.logo_position' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.logo_position')
                                                        ->textInput()->hint('eg: top_left, bottom_left, 1000x400..'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                                'app_mode' => [
                                                    'label' => FHtml::t('App Mode'),
                                                    'value'         => $form->fieldNoLabel($model, 'app_mode')
                                                        ->select(['', 'api', 'homepage'])
                                                    //->select([['id' => 0, 'name' => ''], ['id' => 1, 'name' => 'API'], ['id' => 2, 'name' => 'Homepage']]),
                                                ],
                                                'homepage' => [
                                                    'label' => FHtml::t('homepage'),
                                                    'value'         => $form->fieldNoLabel($model, 'homepage')
                                                        ->textInput()
                                                ],
                                                'website_timer' => [
                                                    'label' => FHtml::t('Interval to close website'),
                                                    'value'         => $form->fieldNoLabel($model, 'website_timer')
                                                        ->numericInput()->hint("seconds")
                                                ],
                                                'smartads.start_time' => [
                                                    'label' => FHtml::t('Business Hours Start'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.start_time')
                                                        ->time()->hint("Before this hour, black screen is shown"),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.end_time' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.end_time')
                                                        ->time()->hint("After this hour, black screen is shown"),
                                                    'label' => FHtml::t('Business Hours End'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'timezone' => [
                                                    'value'         => $form->fieldNoLabel($model, 'timezone')
                                                        ->textInput()->hint('Default: Asia/Ho_Chi_Minh'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ], 'license' => [
                                                    'value'         => $form->fieldNoLabel($model, 'license')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ]
                                            ]
                                        ]) ?>


                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'API',
                                            'hide_field' => false,
                                            'open' => true,

                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'smartads.page_refresh_interval' => [
                                                    'label' => FHtml::t('Interval to load schdules'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.page_refresh_interval')
                                                        ->numericInput()->hint('seconds'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.app_refresh_interval' => [
                                                    'label' => FHtml::t('Interval to refresh app'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.app_refresh_interval')
                                                        ->numericInput()->hint('seconds'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.main_timer' => [
                                                    'label' => FHtml::t('Interval of app Timer'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.main_timer')
                                                        ->numericInput()->hint('seconds'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.autoRestartAppWithUnCaughtError' => [
                                                    'label' => FHtml::t('Xử lý lỗi trên app'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.autoRestartAppWithUnCaughtError')
                                                        ->select([['id' => 0, 'name' => 'Thoát app + Dùng Mint để báo lỗi qua email.'], ['id' => 1, 'name' => 'Tự động khởi động lại app']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.default_device_status' => [
                                                    'label' => FHtml::t('Tự động đăng ký thiết bị mới'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.default_device_status')
                                                        ->select([['id' => 0, 'name' => 'Không. Admin phải duyệt trước'], ['id' => 1, 'name' => 'Tự động đăng ký thiết bị mới']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.campaign_has_time' => [
                                                    'label' => FHtml::t('Chế độ thời gian trong kịch bản'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.campaign_has_time')
                                                        ->select([['id' => 0, 'name' => 'Không có'], ['id' => 1, 'name' => 'Lặp kịch bản trong khoảng thời gian']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                            ]
                                        ]) ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'Content Display',
                                            'hide_field' => false,
                                            'open' => true,

                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'smartscreen.convert_content_to_iframe' => [
                                                    'label' => FHtml::t('Show Slide'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.convert_content_to_iframe')
                                                        ->select([['id' => 0, 'name' => 'Use App'], ['id' => 1, 'name' => 'Use Iframe At Server (Online required)']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.convert_image_to_iframe' => [
                                                    'label' => FHtml::t('Show Images'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.convert_image_to_iframe')
                                                        ->select([['id' => 0, 'name' => 'Use App'], ['id' => 1, 'name' => 'Use Webview'], ['id' => 2, 'name' => 'Use Iframe At Server (Online required)']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.convert_video_to_iframe' => [
                                                    'label' => FHtml::t('Show Videos'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.convert_video_to_iframe')
                                                        ->select([['id' => 0, 'name' => 'Use App'], ['id' => 1, 'name' => 'Use Webview'], ['id' => 2, 'name' => 'Use Iframe At Server (Online required)']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.convert_marquee_to_iframe' => [
                                                    'label' => FHtml::t('Show Marquee'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.convert_marquee_to_iframe')
                                                        ->select([['id' => 0, 'name' => 'Use App'], ['id' => 1, 'name' => 'Use Webview'], ['id' => 2, 'name' => 'Use Iframe At Server (Online required)']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.VideoFillMode' => [
                                                    'label' => FHtml::t('Video Fill Mode'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.VideoFillMode')
                                                        ->select([['id' => 0, 'name' => 'Fit Inside Screen, Keep Ratio'], ['id' => 1, 'name' => 'Always Full Screen'], ['id' => 2, 'name' => 'Center Crop']]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ]

                                            ]
                                        ]) ?>


                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'Testing',
                                            'hide_field' => true,
                                            'open' => false,

                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'smartscreen.test_devices' => [
                                                    'label' => FHtml::t('Test Devices'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.test_devices')
                                                        ->textarea(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.test_start_time' => [
                                                    'label' => FHtml::t('Test Start Time'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.test_start_time')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.test_end_time' => [
                                                    'label' => FHtml::t('Test End Time'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.test_end_time')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartscreen.test_schedule_duration' => [
                                                    'label' => FHtml::t('Test Duration'),
                                                    'value'         => $form->fieldNoLabel($model, 'smartscreen.test_schedule_duration')
                                                        ->textInput()->hint('mins'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]
                                        ]) ?>

                                        <?= \common\widgets\FFormTable::widget([

                                            'model'      => $model,
                                            'title'      => 'HIS SERVER',
                                            'form'       => $form,
                                            'hide_field' => true,
                                            'open' => false,

                                            'columns'    => 1,
                                            'attributes' => [
                                                'smartads.his_enabled' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.his_enabled')
                                                        ->boolean(),
                                                ],
                                                'lcd.rowCount' => [
                                                    'value'         => $form->fieldNoLabel($model, 'lcd.rowCount')
                                                        ->select([2, 3, 4, 5, 6]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'smartads.his_api_server' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.his_api_server')
                                                        ->textInput(),
                                                ],
                                                'smartads.his_api_patientslist' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.his_api_patientslist')
                                                        ->textInput(),
                                                ],
                                                'smartads.his_api_departmentslist' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.default_his_api_departmentslistduration')
                                                        ->textInput(),
                                                ],
                                                'smartads.his_api_roomslist' => [
                                                    'value'         => $form->fieldNoLabel($model, 'smartads.his_api_roomslist')
                                                        ->textInput(),
                                                ],
                                            ]
                                        ]) ?>

                                        <?= \common\widgets\FFormTable::widget([
                                            'model'      => $model,
                                            'title'      => 'Socket',
                                            'hide_field' => false,
                                            'open' => false,

                                            'form'       => $form,
                                            'columns'    => 2,
                                            'attributes' => [
                                                'nodejs.enabled' => [
                                                    'label' => 'Enable Socket',
                                                    'value'         => $form->fieldNoLabel($model, 'nodejs.enabled')
                                                        ->boolean(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'nodejs.port' => [
                                                    'label' => 'Socket Port',

                                                    'value'         => $form->fieldNoLabel($model, 'nodejs.port')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'nodejs.server' => [
                                                    'label' => 'Socket server',
                                                    'value'         => $form->fieldNoLabel($model, 'nodejs.server')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'nodejs.command' => [
                                                    'value'         => $form->fieldNoLabel($model, 'nodejs.command')
                                                        ->textInput(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]
                                        ]) ?>

                                    </div>
                                </div>


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
<?php if ($newForm) \common\widgets\FActiveForm::end(); ?>