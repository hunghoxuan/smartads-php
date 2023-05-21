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

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen-schedules';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenSchedules */
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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?php $form = FActiveForm::begin([
    'id' => 'smartscreen-schedules-form',
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
                            : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?> </span>
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
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">

                                        <?php
                                        $list_device = ArrayHelper::map(SmartscreenStationAPI::find()
                                            ->all(), 'id', 'name');
                                        $list_layout = ArrayHelper::map(SmartscreenLayouts::find()
                                            ->all(), 'id', 'name');
                                        ?>

                                        <?php
                                        $disabled = false;
                                        if ($currentAction == 'update') {
                                            $disabled = true;
                                        }

                                        ?>
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'device_id' => ['value' => $form->fieldNoLabel($model, 'device_id')->widget(Select2::classname(), [
                                                'data' => $list_device,
                                                'options' => ['placeholder' => 'Select a device ...', 'multiple' => true, 'disabled' => $disabled],
                                                'pluginOptions' => [
                                                    'tags' => true,
                                                    'tokenSeparators' => [',', ' '],
                                                ],
                                            ])],

                                            'date' => ['value' => $form->fieldNoLabel($model, 'date')->widget(DatePicker::classname(), [
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'dd-mm-yyyy',
                                                    'todayHighlight' => true,
                                                ]
                                            ])],
                                        ]]); ?>

                                        <div class="clearfix"></div>

                                        <h4>Schedules</h4>


                                        <div class="list_schedules">
                                            <?= $form->field($model, 'layout_id')->widget(MultipleInput::className(), [
                                                'addButtonPosition' => MultipleInput::POS_HEADER,
                                                'columns' => [
                                                    [
                                                        'name' => 'start_time',
                                                        'type' => \kartik\time\TimePicker::className(),
                                                        'title' => 'Start Time',
                                                        'options' => [
                                                            'pluginOptions' => [
                                                                'format' => 'dd.mm.yyyy',
                                                                'todayHighlight' => true
                                                            ]
                                                        ],
                                                    ],
                                                    [
                                                        'name' => 'layout',
                                                        'type' => kartik\select2\Select2::className(),
                                                        'title' => 'Layout',
                                                        'options' => [
                                                            'data' => $list_layout,
                                                            'options' => [
                                                                'placeholder' => 'Select a layout ...',
                                                                'onchange' => <<< JS
                                                                    var selectId = $(this).attr('id');
                                                                    var tr = $(this).closest('tr');
                                                                    var tbody = $(this).closest('tbody');
                                                                    $.post("get-content?layout_id=" + $(this).val() + "&selectId=" + selectId, function(data){
                                                                        tbody.find('.child_' + selectId).remove();
                                                                        tr.after(data);
                                                                        $(".contentSelect2").select2();
                                                                    });
JS
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true
                                                            ],
                                                        ]
                                                    ],
                                                    [
                                                        'name' => 'loop',
                                                        'title' => '',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'form-control visibility-hidden'
                                                        ]
                                                    ],
                                                    [
                                                        'name' => 'duration',
                                                        'title' => '',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'form-control visibility-hidden'
                                                        ]
                                                    ]
                                                ]
                                            ])->label(false);
                                            ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => []]); ?>

                                        <hr />
                                        <?php echo FormObjectFile::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]);
                                        ?>


                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?php echo FormObjectAttributes::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]);
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?php $type = FHtml::getFieldValue($model, 'type');

            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
            ?>
            <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>

        </div>
        <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">

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
    var url = '$baseUrl' + '/index.php/smartscreen/smartscreen-schedules/get-content';
    var device_id = '$model->device_id';
    var date = '$model->date';
    
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
                var link_a = "/backend/web/index.php/smartscreen/smartscreen-content/update?id="+content_id;
                $(view_content).attr("data-link", link_a);
            }else{
                $(view_content).attr("data-link", '');
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