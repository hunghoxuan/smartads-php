<?php

use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use backend\modules\smartscreen\Smartscreen;

$form_Type = $this->params['activeForm_type'];

$moduleName  = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey   = 'smartscreen-schedules';

$currentRole   = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit      = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete    = FHtml::isInRole('', 'delete', $currentRole);
$edit_type    = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
$id   = FHtml::getRequestParam('id');

$list_device   = \backend\modules\smartscreen\models\SmartscreenStation::findAllForCombo();
$list_layout   = SmartscreenLayouts::findAllForCombo();
$list_channels = \backend\modules\smartscreen\models\SmartscreenChannels::findAllForCombo();
$list_campaigns = \backend\modules\smartscreen\models\SmartscreenCampaigns::findAllForCombo();

$basic_mode   = !$model->isNewRecord && ($model->type == Smartscreen::SCHEDULE_TYPE_BASIC || empty($model->type) || !empty($model->list_content) || $model->isNewRecord || !empty($model->list_content));
$advance_mode = !$model->isNewRecord && (!$basic_mode || $model->type == Smartscreen::SCHEDULE_TYPE_ADVANCE);

if ($model->isNewRecord) {
    $model->start_time = FHtml::getRequestParam('start_time',  \backend\modules\smartscreen\Smartscreen::settingStartTimeWorking());
    $model->duration = \backend\modules\smartscreen\Smartscreen::getDefaultDuration();
    $model->is_active = true;
    $model->{\backend\modules\smartscreen\models\SmartscreenSchedules::FIELD_STATUS}  = 1;
}

if (empty($model->type)) {
    $model->type = ($model->isNewRecord || $basic_mode) ? Smartscreen::SCHEDULE_TYPE_BASIC : Smartscreen::SCHEDULE_TYPE_ADVANCE;
}
$returnUrl = $model->getReturnUrl();
$cancelButton = "<a data-pjax='0' class='btn btn-default' href='$returnUrl' />" . FHtml::t('Cancel') . "</a>";

$multipleTimes = empty($model->start_time);

$date = isset($date) ? $date : FHtml::getRequestParam('date');
$channel_id = isset($channel_id) ? $channel_id : Smartscreen::getCurrentChannelId($model);
$device_id = isset($device_id) ? $device_id : Smartscreen::getCurrentDeviceId($model);
$campaign_id = isset($campaign_id) ? $campaign_id : Smartscreen::getCurrentCampaignId($model);
$start_time = isset($start_time) ? $start_time : FHtml::getRequestParam('start_time');

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenSchedules */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title                    = FHtml::t($moduleTitle);
    $this->params['mainIcon']       = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button'     => array(),
        'dropdown'   => array(),
    );
} ?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?php $form = FActiveForm::begin([
    'id'                     => 'smartscreen-schedules-form',
    'type'                   => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig'             => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly'             => false, // check the Role here
    'readonly'               => !$canEdit, // check the Role here
    'edit_type'              => $edit_type,
    'display_type'           => $display_type,
    'enableClientValidation' => true,
    'enableAjaxValidation'   => false,
    'options'                => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);

if (json_decode($model->channel_id, true) != null) {
    $model->channel_id =  json_decode($model->channel_id, true);
}

if (json_decode($model->device_id, true) != null) {
    $model->device_id =  json_decode($model->device_id, true);
}

$type = FHtml::getFieldValue($model, 'type');
$canDelete = false;
$deleteUrl = !$model->isNewRecord ? FHtml::createUrl('/smartscreen/smartscreen-schedules/delete', Smartscreen::getCurrentParams(['id' => $model->id])) : '';
$deleteButton = "<a data-pjax='0' href='$deleteUrl' class='btn btn-danger pull-right'>" . FHtml::t('Delete') . "</a>";

$backToUpdate = (!$model->isNewRecord && empty($model->start_time));
$cancelUrl = $backToUpdate ? FHtml::createUrl('/smartscreen/smartscreen-schedules/update', Smartscreen::getCurrentParams(['id' => $model->id, 'action' => 'cancel'])) : FHtml::createUrl('/smartscreen/smartscreen-schedules/index',  Smartscreen::getCurrentParams([]));
$cancelButton = "<a data-pjax='0' href='$cancelUrl' class='btn btn-default'>" . FHtml::t('Cancel') . "</a>";
$buttons = '{save}{delete}' . $cancelButton . $deleteButton;
if (empty($model->name))
    $model->name = '';
$model->_content_id = $model->content_id;
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
                        </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>

                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">

                                    <div class="col-md-9">

                                        <?php echo FFormTable::widget([
                                            'hide_field' => false,
                                            'model'      => $model,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                '_times' => [
                                                    'visible' => !$model->isNewRecord && $multipleTimes,
                                                    'value'         => $form->fieldNoLabel($model, '_times')->widget(MultipleInput::className(), [
                                                        'addButtonPosition' => $model->isNewRecord ? null : MultipleInput::POS_HEADER,
                                                        //'max'               => 1,
                                                        'min'               => 1,

                                                        'columns' => [
                                                            [
                                                                'title' => FHtml::t('Start Time'),
                                                                'name' => '_start_time',
                                                                'type'    => \common\widgets\FTimeInput::className(),

                                                            ],
                                                            [
                                                                'title' => FHtml::t('End Time'),
                                                                'name' => '_end_time',
                                                                'type'    => \common\widgets\FTimeInput::className(),

                                                            ],
                                                            [
                                                                'title' => FHtml::t('Duration') . ' (mins)',
                                                                'name' => '_duration',
                                                                'type'    => \common\widgets\FNumericInput::className(),

                                                            ],
                                                        ]
                                                    ]),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],


                                                'type' => [
                                                    'label' => 'Type',
                                                    'required' => true,
                                                    'visible' => $model->isNewRecord,
                                                    'value'         => $form->fieldNoLabel($model, 'type')->select(['basic' => FHtml::t('Fullscreen & Upload new media files'), 'advance' => FHtml::t('Custom Layout & saved Content')]),

                                                    'type'          => $model->isNewRecord ? FHtml::INPUT_RAW : FHtml::INPUT_READONLY
                                                ],
                                                'start_time' => [
                                                    'visible' =>  !empty($start_time) || (!$model->isNewRecord && !$multipleTimes),
                                                    'value'         => $form->fieldNoLabel($model, 'start_time')->time(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'end_time' => [
                                                    'label' => FHtml::t('End Time'),
                                                    'visible' =>  !empty($start_time) || (!$model->isNewRecord && !$multipleTimes),
                                                    'value'         => $form->fieldNoLabel($model, 'end_time')->time(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'duration'   => [
                                                    'visible' => !$model->isNewRecord && !$multipleTimes,
                                                    'value'         => $form->fieldNoLabel($model, 'duration')->numeric()->hint('mins'),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'campaign_id' => [
                                                    'visible'  => $model->isNewRecord && !empty(FHtml::getRequestParam('campaign_id')),
                                                    'value'         => $form->fieldNoLabel($model, 'campaign_id')->dropDownList($list_campaigns),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'channel_id' => [
                                                    'visible'  => $model->isNewRecord && !empty(FHtml::getRequestParam('channel_id')),
                                                    'value'         => $form->fieldNoLabel($model, 'channel_id')->dropDownList($list_channels),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'is_active' => [
                                                    'visible'  => !$model->isNewRecord,
                                                    'value'         => $form->fieldNoLabel($model, 'is_active')->boolean(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                                '_content_id' => [
                                                    'label' => FHtml::t('Content'),
                                                    'visible'  => $basic_mode,
                                                    'value'         => $form->fieldNoLabel($model, '_content_id')->dropDownList(\backend\modules\smartscreen\models\SmartscreenContent::findAllForCombo()),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]
                                        ]); ?>

                                        <?php echo FFormTable::widget([
                                            'id' => 'content_widget',
                                            'hide_field' => false,
                                            'model'      => $model,
                                            'form'       => $form,
                                            'columns'    => 1,
                                            'attributes' => [
                                                'name' => [
                                                    'label' => FHtml::t('Content') . ' (' . FHtml::t('Create') . ')',
                                                    'visible'  => $basic_mode && empty($model->content_id),
                                                    'value'         => $form->fieldNoLabel($model, 'name')->textInput()->hint(empty($model->content_id) ? 'Leave blank if you dont want to save files to content library' : ''),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                '_Content' => [
                                                    'visible'       => $basic_mode,
                                                    'label' => '',

                                                    'value'         => $form->fieldNoLabel($model, 'list_content')->widget(MultipleInput::className(), [
                                                        'min'               => 0,
                                                        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                                                        'columns'           => [
                                                            [
                                                                'name'          => 'command',
                                                                'type'          => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
                                                                'enableError'   => true,
                                                                'title'         => FHtml::t('common', 'Type'),
                                                                'items' =>  [
                                                                    '' => FHtml::NULL_VALUE,
                                                                    'marquee' => FHtml::t('common', 'Scrolling Text'),
                                                                    'image' => FHtml::t('common', 'Image'),
                                                                    'video' => FHtml::t('common', 'Video'),
                                                                    'embed' => FHtml::t('common', 'Embed HTML'),
                                                                    'url' => FHtml::t('common', 'URL Link'),
                                                                    'youtube' => FHtml::t('common', 'Youtube'),
                                                                    'facebook' => FHtml::t('common', 'Facebook'),
                                                                ],
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-2',
                                                                ]
                                                            ],

                                                            [
                                                                'name'        => 'description',
                                                                'enableError' => true,
                                                                'title'       => FHtml::t('common', 'Content'),
                                                                'type'        => 'textarea',

                                                                'options'       => [],
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-4',
                                                                ]
                                                            ],
                                                            //
                                                            [
                                                                'name'          => 'file_duration',
                                                                'title'         => FHtml::t('common', 'Duration'),
                                                                'options'       => [
                                                                    'class' => 'col-md-1',
                                                                ],
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-1',
                                                                ]
                                                            ],
                                                            [
                                                                'name'          => 'file_kind',
                                                                'type'          => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
                                                                'enableError'   => true,
                                                                'title'         => FHtml::t('common', ''),
                                                                'items'         => \backend\modules\smartscreen\models\SmartscreenSchedules::getDurationKindArray(),
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-2',
                                                                ]
                                                            ],
                                                            //
                                                            [
                                                                'name'          => 'id',
                                                                'options'       => [
                                                                    'style' => 'border:none;width:0px;visible:none',
                                                                ],
                                                                'headerOptions' => [
                                                                    'style' => 'border:none;width:0px;visible:none',
                                                                ]
                                                            ],
                                                            [
                                                                'value'         => function ($data) {
                                                                    return FHtml::showImage(FHtml::getFieldValue($data, 'file'), 'smartscreen-file', '80px', '50px', 'margin-top:-15px', 'btn btn-large', false, 'download');
                                                                },
                                                                'type'          => 'static',
                                                                'name'          => 'id',
                                                                'enableError'   => true,
                                                                'title'         => '',
                                                                'options'       => [
                                                                    'style' => 'border:none; margin-top:-10px;width:50px',
                                                                ],
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-1',
                                                                ],
                                                            ],
                                                            [
                                                                'name'          => '_file_upload',
                                                                'type'          => \kartik\widgets\FileInput::className(),
                                                                'options'       => [
                                                                    'options'       => ['accept' => 'image/*,video/*', 'class' => 'small_size', 'multiple' => false],
                                                                    'pluginOptions' => [
                                                                        'browseLabel' => '',
                                                                        'removeLabel' => '',
                                                                        'model'       => $model,
                                                                        'maxFileSize' => FHtml::settingMaxFileSize(),
                                                                        'showPreview' => false,
                                                                        'showCaption' => false,
                                                                        'showRemove'  => true,
                                                                        'showUpload'  => false
                                                                    ]
                                                                ],
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-1'
                                                                ]
                                                            ],
                                                            [
                                                                'name' => 'sort_order',
                                                                'title' => FHtml::t('common', 'STT'),
                                                                'headerOptions' => [
                                                                    'class' => 'col-md-1',
                                                                ]
                                                            ],

                                                        ]
                                                    ]),
                                                    'columnOptions' => ['colspan' => 3],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                                '_schedules' => [
                                                    'visible'       => $advance_mode,
                                                    'value'         => $form->fieldNoLabel($model, 'layout_id')->widget(MultipleInput::className(), [
                                                        'addButtonPosition' => $model->isNewRecord ? null : MultipleInput::POS_HEADER,
                                                        'max'               => 1,
                                                        'min'               => 1,

                                                        'columns' => [

                                                            [
                                                                'name'    => 'layout',
                                                                'type'    => kartik\select2\Select2::className(),
                                                                'title'   => 'Layout',
                                                                'options' => [
                                                                    'data'          => $list_layout,
                                                                    'options'       => [
                                                                        'class'       => 'schedules_layout_id',
                                                                        'placeholder' => FHtml::t('common',  'Select a layout') . '...',
                                                                    ],
                                                                    'pluginOptions' => [
                                                                        'allowClear' => true,
                                                                        'class'      => 'col-md-6'
                                                                    ],
                                                                ]
                                                            ],

                                                            [
                                                                'name' => 'id',

                                                                'options'       => [
                                                                    'style' => 'border:none;width:0px;visible:none',
                                                                ],
                                                                'headerOptions' => [
                                                                    'style' => 'border:none;width:0px;visible:none',
                                                                ]
                                                            ],
                                                        ]
                                                    ]),
                                                    'columnOptions' => ['colspan' => 3],
                                                    'type'          => FHtml::INPUT_RAW
                                                ]
                                            ]
                                        ]); ?>

                                        <?php if ($model->isNewRecord) {

                                        ?>
                                            <button type="submit" class="btn btn-primary" onclick="submitForm(&quot;save&quot;)"><i class="fa fa-save"></i> Tiếp tục</button>
                                            <?= $cancelButton ?>

                                        <?php } ?>

                                    </div>

                                    <?php if (!$model->isNewRecord) { ?>
                                        <div class="col-md-3 form-label">
                                            <?php
                                            $disabled = false;
                                            if ($currentAction == 'update' && empty($id)) {
                                                $disabled = true;
                                            }
                                            ?>
                                            <?php echo FFormTable::widget([
                                                'hide_field' => false,
                                                'model'      => $model,
                                                'form'       => $form,
                                                //'title' => FHtml::t('common', 'Devices'),
                                                'type'                   => ActiveForm::TYPE_VERTICAL,

                                                'columns'    => 1,
                                                'attributes' => [
                                                    'campaign_id' => [
                                                        'visible'  => !$model->isNewRecord,
                                                        'readonly' => true,
                                                        'value'         => $form->fieldNoLabel($model, 'campaign_id')->dropDownList($list_campaigns, ['disabled' => !empty(FHtml::getRequestParam('campaign_id'))]),
                                                        'columnOptions' => ['colspan' => 1, 'readonly' => true],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                ]
                                            ]);

                                            echo FFormTable::widget([
                                                'id' => 'campaign_widget',
                                                'overview' => 'Ghi chú: Không nhập dữ liệu nếu muốn áp dụng cho tất cả trường hợp',
                                                'title' => 'Phạm vi áp dụng',
                                                'hide_field' => false,
                                                'model'      => $model,
                                                'form'       => $form,
                                                //'title' => FHtml::t('common', 'Devices'),
                                                'type'                   => ActiveForm::TYPE_VERTICAL,

                                                'columns'    => 1,
                                                'attributes' => [
                                                    'channel_id' => [
                                                        'visible' => empty($model->campaign_id),
                                                        'value'         => $form->fieldNoLabel($model, 'channel_id')->widget(Select2::classname(), [
                                                            'data'    => $list_channels,
                                                            'options' => ['placeholder' => 'Tất cả Nhóm thiết bị', 'multiple' => false, 'disabled' => $disabled]
                                                        ]),

                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'device_id'  => [
                                                        'visible' => empty($model->campaign_id),

                                                        'value'         => $form->fieldNoLabel($model, 'device_id')->widget(Select2::classname(), [
                                                            'data'          => $list_device,
                                                            'options'       => ['placeholder' => 'Tất cả thiết bị', 'multiple' => true, 'disabled' => $disabled],
                                                            'pluginOptions' => [
                                                                'tags'            => true,
                                                                'tokenSeparators' => [',', ' '],
                                                            ],
                                                        ]),

                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'date'     => [
                                                        'visible' => empty($model->campaign_id),

                                                        'value'         => $form->fieldNoLabel($model, 'date')->date(),

                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'date_end' => [
                                                        'visible' => empty($model->campaign_id),

                                                        'value'         => $form->fieldNoLabel($model, 'date_end')->date(),

                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'days'     => [
                                                        'visible' => empty($model->campaign_id),
                                                        'value'         => $form->fieldNoLabel($model, 'days')->checkBoxList(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
                                                        'columnOptions' => ['colspan' => 3],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                ]
                                            ]);
                                            if (!empty($model->campaign_id))
                                                echo $model->showPreview(true);
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?= $model->isNewRecord ?  '' : ((FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete, $buttons)) ?>
        </div>
    </div>
</div>
<?php if (!$model->isNewRecord  && empty(FHtml::getRequestParam('layout'))) {
    $device_id = Smartscreen::getCurrentDeviceId($model);
?>
    <div style="width: 100%; height: 700px; margin-bottom: 50px; background-color: #fefefe">
        <div style="margin-right: 10px; float:right"><a data-pjax="0" target="_blank" href="<?= FHtml::createUrl('/smartscreen/schedules', ['id' => $model->id, 'device_id' => $device_id, 'layout' => 'no', 'auto_refresh' => 0]) ?>">Full screen</a> </div>
        <iframe frameborder="0" src="<?= FHtml::createUrl('/smartscreen/schedules', ['id' => $model->id, 'device_id' => $device_id, 'layout' => 'no', 'auto_refresh' => 0]) ?>" width="100%" height="100%" />
    </div>
<?php  } ?>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>

<style>
    .demo-layout {
        background-color: #666;
        width: 100%;
        height: 200px;
    }

    .div-layout {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .visibility-hidden {
        visibility: hidden;
    }
</style>

<?php
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
$old_content_id = !empty($model->content_id) ? $model->content_id : 0;

$script = <<< JS
    var old_content_id = $old_content_id;
    $('.schedules_layout_id').on('change', function() {
        var selectId = $(this).attr('id');
        var tr = $(this).closest('tr');
        var tbody = $(this).closest('tbody');
        let layout_id = $(this).val();
        $.ajax({
            url :   "get-content",
            type : 'get',
            data : {layout_id : layout_id, selectId: selectId, scheduleId: '{$id}', _token: $('meta[name="csrf-token"]').attr('content')},

        }).success(function(data) {
            tbody.find('.child_' + selectId).remove();
            tr.after(data);
            $(".contentSelect2").select2();
        });
        // $.post("get-content?layout_id=" + $(this).val() + "&selectId=" + selectId, function(data){
        //     tbody.find('.child_' + selectId).remove();
            // tr.after(data);
            // $(".contentSelect2").select2();
        // });
    }).trigger('change');
    
    var url = '$baseUrl' + '/index.php/smartscreen/smartscreen-schedules/get-content';
    
    $('#w1').on('afterDeleteRow', function(e, item){       
        var selectId = item.find('.list-cell__layout').find('select').attr('id');
        $(this).find('.child_' + selectId).remove();
    });
    
    //get frame tu layout
    $('#w1 tr.multiple-input-list__item').each(function(index) {
         var layout_id = $(this).find('.list-cell__layout').find(':selected').val();
         var selectId = $(this).find('.list-cell__layout').find('select').attr('id');
         var numberPattern = /\d+/g;
         var scheduleId =  parseInt(selectId.match( numberPattern ));

         var tr = $(this);

         $.post("get-content?layout_id=" + layout_id + "&selectId=" + selectId + "&scheduleId=" + scheduleId , function(data) {
            tr.after(data);
            $(".contentSelect2").select2();
         });
    });
    
    //gan link vao view content khi thay doi noi dung
    $(".list_schedules").on("change", ".contentSelect2", function(){
      
        $(this).find(":selected").each(function () {
            var content_id = $(this).val();
            var view_content = $(this).closest('tr').find('.view_content .data-link').first();
            
            if (content_id){
                var link_a = '$baseUrl' + "/index.php/smartscreen/smartscreen-content/update?id="+content_id;
                $(view_content).attr("data-link", link_a);
            } else {
                $(view_content).val('');
                $(view_content).attr("data-link", '#');
            }
        });
    });
    
    $("#w1").on("click",".data-link", function(){
        var link = $(this).attr('data-link');
        if (link.length > 0){
             window.open(link, '_blank');
        }
    });
    
    $('select[name="SmartscreenSchedules[campaign_id]"]').change(function () {
            var value = this.value;
            console.log(value);
            if (value != '' && value != undefined) {
                $('#campaign_widget').show();
            } else {
                $('#campaign_widget').hide();
            }
            
        });

     $('select[name="SmartscreenSchedules[_content_id]"]').change(function () {
            var value = this.value;
            console.log(value);
            if (value != '' && value != undefined && value != 0 && value != old_content_id) {
                $('#content_widget').hide();
            } else {
                $('#content_widget').show();
            }
            
        });
    
JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>