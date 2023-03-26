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
use backend\modules\smartscreen\models\SmartscreenSchedulesSearch;
use backend\modules\smartscreen\models\SmartscreenSchedules;

$form_Type = $this->params['activeForm_type'];

$moduleName  = 'SmartscreenSchedules';
$moduleTitle = 'Campaigns';
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

if ($model->isNewRecord) {

    $model->is_active = 1;
    $model->date = date('Y-m-d');
    $model->type = Smartscreen::SCHEDULE_TYPE_CAMPAIGN;
}

$returnUrl = $model->getReturnUrl();
$returnUrl = str_replace('-schedules', '-campaigns', $returnUrl);
$cancelButton = "<a data-pjax='0' class='btn btn-default' href='$returnUrl' />" . FHtml::t('Cancel') . "</a>";

$device_id = Smartscreen::getCurrentDeviceId();
$channel_id = Smartscreen::getCurrentChannelId();
$campaignHasTime = FHtml::setting('smartscreen.campaign_has_time', false);

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

                                    <div class="col-md-12">
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
											'columns'    => 1,
                                            'attributes' => [
                                                'name' => [
                                                    'value'         => $form->fieldNoLabel($model, 'name')->textInput(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'channel_id' => [
                                                    'value'         => $form->fieldNoLabel($model, 'channel_id')->widget(Select2::classname(), [
                                                        'data'    => $list_channels,
                                                        'options' => ['placeholder' => 'Tất cả Nhóm thiết bị', 'multiple' => false, 'disabled' => $disabled]
                                                    ]),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'device_id'  => [
                                                    'value'         => $form->fieldNoLabel($model, 'device_id')->widget(Select2::classname(), [
                                                        'data'          => $list_device,
                                                        'options'       => ['placeholder' => 'Tất cả thiết bị', 'multiple' => true, 'disabled' => $disabled],
                                                        'pluginOptions' => [
                                                            'tags'            => true,
                                                            'tokenSeparators' => [',', ' '],
                                                        ],
                                                    ]),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]
										]); ?>


                                        <?php echo FFormTable::widget([
                                            'hide_field' => false,
                                            'model'      => $model,
                                            'form'       => $form,
                                            //'title' => FHtml::t('common', 'Devices'),
                                            'type'                   => ActiveForm::TYPE_HORIZONTAL,

                                            'columns'    => 2,
                                            'attributes' => [


                                                'date'     => [
                                                    'value'         => $form->fieldNoLabel($model, 'date')->date(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'date_end' => [
                                                    'value'         => $form->fieldNoLabel($model, 'date_end')->date(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],

                                                'start_time'     => [
                                                        'visible' => $campaignHasTime,
                                                    'value'         => $form->fieldNoLabel($model, 'start_time')->time(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'end_time'     => [
                                                    'visible' => $campaignHasTime,
                                                    'value'         => $form->fieldNoLabel($model, 'end_time')->time(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'duration'     => [
                                                    'visible' => $campaignHasTime,
                                                    'value'         => $form->fieldNoLabel($model, 'duration')->numeric()->appendText('mins'),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'is_active'     => [
                                                    'value'         => $form->fieldNoLabel($model, 'is_active')->boolean(),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                                'days'     => [
                                                    'value'         => $form->fieldNoLabel($model, 'days')->checkBoxList(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
                                                    'columnOptions' => ['colspan' => 1],
                                                    'type'          => FHtml::INPUT_RAW
                                                ],
                                            ]
                                        ]); ?>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

			<?php $type = FHtml::getFieldValue($model, 'type');
			$canDelete = false;
			$deleteParams = Smartscreen::getCurrentParams(['id' => $model->id]);

			$deleteUrl = !$model->isNewRecord ? FHtml::createUrl('/smartscreen/smartscreen-campaigns/delete', $deleteParams) : '';
			$deleteButton = "<a data-pjax='0' href='$deleteUrl' class='btn btn-danger pull-right'>". FHtml::t('Delete') . "</a>";

            $cancelUrl = $returnUrl;
            $cancelButton = "<a data-pjax='0' href='$cancelUrl' class='btn btn-default'>". FHtml::t('Cancel') . "</a>";
			$buttons = '{save}{delete}' . $cancelButton . $deleteButton;
			?>
			<?= false ?  '' : ((FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete, $buttons)) ?>
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


$script = <<< JS
    
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

         $.post("get-content?layout_id=" + layout_id + "&selectId=" + selectId + "&scheduleId=" + scheduleId , function(data){
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
    
    //$('.schedules_layout_id').val(54);
    //$('.schedules_layout_id').trigger('change');

    
JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>
