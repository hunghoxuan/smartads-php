<?php

use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\models\SmartscreenStation;
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
use common\widgets\FGridView;

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

if ($model->isNewRecord) {
    $model->start_time = FHtml::getRequestParam('start_time',  \backend\modules\smartscreen\Smartscreen::settingStartTimeWorking());
    $model->duration = \backend\modules\smartscreen\Smartscreen::getDefaultDuration();
    $model->is_active = true;
    $model->{\backend\modules\smartscreen\models\SmartscreenSchedules::FIELD_STATUS}  = 1;
    $model->type = Smartscreen::SCHEDULE_TYPE_ADVANCE;
}

$returnUrl = $model->getReturnUrl();
$cancelButton = "<a data-pjax='0' class='btn btn-default' href='$returnUrl' />" . FHtml::t('Cancel') . "</a>";

$date = isset($date) ? $date : FHtml::getRequestParam('date');
$channel_id = isset($channel_id) ? $channel_id : Smartscreen::getCurrentChannelId($model);
$device_id = isset($device_id) ? $device_id : Smartscreen::getCurrentDeviceId($model);
$campaign_id = isset($campaign_id) ? $campaign_id : Smartscreen::getCurrentCampaignId($model);
$start_time = isset($start_time) ? $start_time : FHtml::getRequestParam('start_time');

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
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget([
                                            'hide_field' => false,
                                            'model'      => $model,
                                            'form'       => $form,
                                            'columns'    => 3,
                                            'attributes' => [

                                                'start_time' => [
                                                    'value'         => $form->fieldNoLabel($model, 'start_time')->time(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'end_time' => [
                                                    'label' => FHtml::t('End Time'),
                                                    'value'         => $form->fieldNoLabel($model, 'end_time')->time(),

                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'is_active' => [
                                                    'visible'  => true,
                                                    'value'         => $form->fieldNoLabel($model, 'is_active')->boolean(),

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

                                                '_schedules' => [
                                                    'label' => false,
                                                    'value'         => $form->fieldNoLabel($model, 'layout_id')->widget(MultipleInput::className(), [
                                                        'addButtonPosition' => $model->isNewRecord ? null : MultipleInput::POS_HEADER,
                                                        'max'               => 1,
                                                        'min'               => 1,

                                                        'columns' => [

                                                            [
                                                                'name'    => 'layout',
                                                                'type'    => kartik\select2\Select2::className(),
                                                                'title'   => FHtml::t('Content'),
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


                                    </div>


                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?= ((FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete, $buttons)) ?>
        </div>

        <div class="col-md-3" style="background:white">

            <?php if (!$model->isNewRecord  && empty(FHtml::getRequestParam('layout'))) {
                $device_id = Smartscreen::getCurrentDeviceId($model);
            ?>
                <div class="row">
                    <div style="width: 100%; height: 250px; margin-bottom: 50px; background-color: #fefefe">
                        <div style="margin-right: 10px; float:right"><a data-pjax="0" target="_blank" href="<?= FHtml::createUrl('/smartscreen/schedules', ['id' => $model->id, 'device_id' => $device_id, 'layout' => 'no', 'auto_refresh' => 0]) ?>">Full screen</a> </div>
                        <iframe frameborder="0" src="<?= FHtml::createUrl('/smartscreen/schedules', ['id' => $model->id, 'device_id' => $device_id, 'layout' => 'no', 'auto_refresh' => 0]) ?>" width="100%" height="100%"></iframe>
                    </div>
                </div>
            <?php  } ?>
            <div class="row">
                <?php
                $disabled = false;
                if ($currentAction == 'update' && empty($id)) {
                    $disabled = true;
                }
                ?>
                <?php


                if (!empty($model->campaign_id)) {
                    echo $model->showPreview(true);
                } else {
                    echo \common\widgets\FFormTable::widget([
                        'hide_field' => false,
                        'type' => \kartik\form\ActiveForm::TYPE_VERTICAL,
                        'model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                            'campaign_id' => ['value' => $form->fieldNoLabel($model, 'campaign_id')->dropdown($list_campaigns)],
                            'channel_id' => ['value' => $form->fieldNoLabel($model, 'channel_id')->dropdown($list_channels)],
                            'device_id[]' => ['name' => 'device_id[]', 'value' => $form->fieldNoLabel($model, 'device_id')->selectCustomRenderer(SmartscreenStation::findAll(), function ($item, $id) {
                                $selected = ($item->id == $id || (is_array($id) && in_array($item->id, $id))) ? 'selected' : '';
                                return "<option parent='$item->channel_id' value='$item->id' $selected>$item->name. $item->description (id: $item->id)</option>";
                            }, ['multiple' => true])],
                            'date' => ['value' => $form->fieldNoLabel($model, 'date')->date()],
                            'date_end' => ['value' => $form->fieldNoLabel($model, 'date_end')->date()],
                            // 'show_all' => ['label' => FHtml::t('Type') . ' ' . FHtml::t('Filter'), 'value' => $form->fieldNoLabel($model, 'show_all')->select([['id' => 0, 'name' => FHtml::t('Active')], ['id' => 1, 'name' => FHtml::t('All')]])],

                        ]
                    ]);
                }
                ?>
            </div>

        </div>
    </div>
</div>

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
            data : { layout_id : layout_id, selectId: selectId, scheduleId: '{$id}', _token: $('meta[name="csrf-token"]').attr('content')},

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
         var scheduleId =  981; // parseInt(selectId.match( numberPattern ));

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

<script>
    document.addEventListener("DOMContentLoaded", function() {

        $('select[name="SmartscreenSchedules[campaign_id]"]').change(function() {
            var value = this.value;
            $('select[name="SmartscreenSchedules[channel_id]"] option').prop('disabled', false).show().filter(function() {
                if (value != '' && value != undefined)
                    return true;
                return false;
            }).prop('disabled', true).prop('selected', false).hide();
            $('select[name="SmartscreenSchedules[device_id]"] option').prop('disabled', false).show().filter(function() {
                if (value != '' && value != undefined)
                    return true;
                return false;
            }).prop('disabled', true).prop('selected', false).hide();
        });


        $('select[name="SmartscreenSchedules[channel_id]"]').change(function() {
            var value = this.value;
            $('select[name="SmartscreenSchedules[device_id]"] option').prop('disabled', false).show().filter(function() {
                var parent = $(this).attr('parent');

                if (value == undefined || value == '')
                    return false;
                return parent && parent !== value;
            }).hide();
        });

        var campaign_id = $('select[name="SmartscreenSchedules[campaign_id]"]').val();
        var channel_id = $('select[name="SmartscreenSchedules[channel_id]"]').val();
        if (campaign_id) {
            $('select[name="SmartscreenSchedules[campaign_id]"]').trigger('change');
        } else if (channel_id) {
            $('select[name="SmartscreenSchedules[channel_id]"]').trigger('change');
        }

    });
</script>