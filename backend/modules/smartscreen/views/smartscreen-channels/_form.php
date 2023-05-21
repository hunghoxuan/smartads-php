<?php

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];
$moduleName = 'SmartscreenChannels';
$moduleTitle = 'Smartscreen Channels';
$moduleKey = 'smartscreen-channels';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type = isset($view_type) ? $view_type : FHtml::getRequestParam('view_type');
if ($view_type == 'full') {
    $col_size1 = 12;
    $col_size2 = 0;
} else {
    $col_size1 = 9;
    $col_size2 = 3;
}
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

$list_device = \yii\helpers\ArrayHelper::map(\backend\modules\smartscreen\models\SmartscreenStationAPI::find()
    ->all(), 'id', 'name');


/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenChannels */
/* @var $form common\widgets\FActiveForm */
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
<?php if ($ajax) {
    Pjax::begin(['id' => 'crud-datatable']);
} ?>
<?php $form = FActiveForm::begin([
    'id' => 'smartscreen-channels-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => $edit_type,
    'display_type' => $display_type,
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<div class="form">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="visible-print">
                    <?= FHtml::isViewAction($currentAction) ? FHtml::showPrintHeader($moduleName) : '' ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) . ":" . FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?></span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3])],
                                            //'content_id' => ['value' => $form->fieldNoLabel($model, 'content_id')->select(\backend\modules\smartscreen\Smartscreen::getContentTypeComboArray())],
                                            //'layout_id' => ['value' => $form->fieldNoLabel($model, 'layout_id')->select([])],
                                            //                                                '_layout_id' => ['value' => $form->fieldNoLabel($model, '_layout_id')->widget(\unclead\multipleinput\MultipleInput::className(), [
                                            //                                                    'addButtonPosition' => false,
                                            //                                                    'columns' => [
                                            //                                                        [
                                            //                                                            'name' => 'layout',
                                            //                                                            'type' => kartik\select2\Select2::className(),
                                            //                                                            'title' => 'Layout',
                                            //                                                            'headerOptions' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //                                                            ],
                                            //                                                            'options' => [
                                            //                                                                'class' => 'col-md-2',
                                            //                                                                'data' => \backend\modules\smartscreen\models\SmartscreenLayouts::findAllForCombo(),
                                            //                                                                'options' => [
                                            //                                                                    'placeholder' => 'Select a layout ...',
                                            //                                                                    'onchange' => <<< JS
                                            //                                                                    var selectId = $(this).attr('id');
                                            //                                                                    var tr = $(this).closest('tr');
                                            //                                                                    var tbody = $(this).closest('tbody');
                                            //                                                                    $.post(" get-content?layout_id=" + $(this).val() + "&selectId=" + selectId, function(data){
                                            //                                                                        tbody.find('.child_' + selectId).remove();
                                            //                                                                        tr.after(data);
                                            //                                                                        $(".contentSelect2").select2();
                                            //                                                                    });
                                            //JS
                                            //                                                                ],
                                            //                                                                'pluginOptions' => [
                                            //                                                                    'allowClear' => true,
                                            //                                                                    'class' => 'col-md-4'
                                            //                                                                ],
                                            //                                                            ]
                                            //                                                        ],
                                            //                                                        [
                                            //                                                            'name' => 'start_time',
                                            //                                                            'title' => false,
                                            //                                                            'options' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //
                                            //                                                                'class' => 'col-md-2'
                                            //                                                            ],
                                            //                                                            'headerOptions' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //                                                            ]
                                            //                                                        ],
                                            //
                                            //
                                            //                                                        [
                                            //                                                            'name' => 'start_time',
                                            //                                                            'title' => false,
                                            //                                                            'options' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //
                                            //                                                                'class' => 'col-md-2'
                                            //                                                            ],
                                            //                                                            'headerOptions' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //                                                            ]
                                            //                                                        ],
                                            //
                                            //                                                        [
                                            //                                                            'name' => 'start_time',
                                            //                                                            'title' => false,
                                            //                                                            'options' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //
                                            //                                                                'class' => 'col-md-2'
                                            //                                                            ],
                                            //                                                            'headerOptions' => [
                                            //                                                                'style' => 'border:none;visible:none',
                                            //                                                            ]
                                            //                                                        ],
                                            //                                                        [
                                            //                                                            'name' => 'id',
                                            //
                                            //                                                            'options' => [
                                            //                                                                'style' => 'border:none;width:0px;visible:none',
                                            //                                                            ],
                                            //                                                            'headerOptions' => [
                                            //                                                                'style' => 'border:none;width:0px;visible:none',
                                            //                                                            ]
                                            //                                                        ],
                                            //                                                    ]
                                            //                                                ])->label(false), 'label' => false],
                                            'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox()],
                                            'is_default' => ['value' => $form->fieldNoLabel($model, 'is_default')->checkbox()],
                                            //'device_id' => ['value' => $form->fieldNoLabel($model, 'device_id')->checkboxList($list_device)],


                                        ]]);  ?>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type)) { ?>
                <?= FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]); ?>
            <?php } ?>
            <?= FHtml::isViewAction($currentAction) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end(); ?>

<?php
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();

$script = <<< JS
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

         $.post(url + "?layout_id=" + layout_id + "&selectId=" + selectId + "&scheduleId=" + scheduleId , function(data){
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

JS;

$this->registerJs($script);

?>