<?php

/**



 * This is the customized model class for table "SmartscreenLayouts".
 */

use backend\modules\smartscreen\models\SmartscreenFrame;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenLayouts';
$moduleTitle = 'Smartscreen Layouts';
$moduleKey = 'smartscreen-layouts';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenLayouts */
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
    'id' => 'smartscreen-layouts-form',
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
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">

                                    <div class="col-md-12">
                                        <?php
                                        $frameData = ArrayHelper::map(SmartscreenFrame::find()
                                            ->all(), 'id', 'name');
                                        ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3])],
                                            'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox()],
                                            'is_default' => ['value' => $form->fieldNoLabel($model, 'is_default')->checkbox()],

                                        ]]); ?>

                                        <div class="clearfix"></div>

                                        <div class="list_frame">
                                            <?= $form->field($model, 'list_frame')->widget(MultipleInput::className(), [
                                                'addButtonPosition' => MultipleInput::POS_HEADER,
                                                'columns' => [
                                                    [
                                                        'name' => 'frame',
                                                        'type' => kartik\select2\Select2::className(),
                                                        'title' => 'Add Frame',
                                                        'options' => [
                                                            'data' => $frameData,
                                                            'options' => ['placeholder' => 'Select a frame ...'],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                                'tags' => true
                                                            ],
                                                        ],
                                                        'headerOptions' => [
                                                            'style' => 'width: 300px',
                                                        ]
                                                    ],

                                                    [
                                                        'name' => 'marginTop',
                                                        'title' => 'Top (%)',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'input-priority'
                                                        ]
                                                    ],
                                                    [
                                                        'name' => 'marginLeft',
                                                        'title' => 'Left (%)',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'input-priority'
                                                        ]
                                                    ],

                                                    [
                                                        'name' => 'percentWidth',
                                                        'title' => 'Width (%)',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'input-priority'
                                                        ]
                                                    ],
                                                    [
                                                        'name' => 'percentHeight',
                                                        'title' => 'Height (%)',
                                                        'enableError' => true,
                                                        'options' => [
                                                            'class' => 'input-priority'
                                                        ]
                                                    ],
                                                    [
                                                        'name' => 'backgroundColor',
                                                        'title' => 'Color',
                                                        'type' => \kartik\color\ColorInput::className(),
                                                        'enableError' => true,
                                                        'options' => [
                                                            'pluginOptions' => [
                                                                'placeholder' => 'Select color ...',
                                                            ],
                                                        ],
                                                    ],
                                                ]
                                            ])->label(false) ?>
                                        </div>


                                        <div class="clearfix"></div>


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
            <div class="portlet">
                <?= isset($full_frame) ? \backend\modules\smartscreen\Smartscreen::showLayoutPreview($full_frame) : '' ?>
            </div>
        </div>
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
</style>

<?php
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();

$script = <<< JS
    var url = '$baseUrl' + '/index.php/smartscreen/smartscreen-layouts/get-frame';
   
    
    $(".list_frame").on("change", ".select2-hidden-accessible", function(){
        
        var classSelect = $(this).attr('id');
        var removeSelect = '.' + classSelect;
        var tr_closet = $(this).closest("tr");
        
        var number = parseInt(classSelect.replace(/[^0-9\.]/g, ''), 10);
        var percentWidth = '#smartscreenlayouts-list_frame-'+ number +'-percentwidth';
        var percentHeight = '#smartscreenlayouts-list_frame-'+ number +'-percentheight';
        var marginTop = '#smartscreenlayouts-list_frame-'+ number +'-margintop';
        var marginLeft = '#smartscreenlayouts-list_frame-'+ number +'-marginleft';
        
        if(($( removeSelect ).length) !== 0) { //check exist class
            $( removeSelect ).remove( removeSelect );
            tr_closet.find("input").val('');
        }
        
        $(this).find(":selected").each(function () {
            var frame_id = $(this).val();
            
            if (frame_id){
                ajaxGetFrame(url, frame_id, classSelect, percentWidth, percentHeight, marginTop, marginLeft);
            }
        });
    });
    
    $( ".list_frame" ).on('input', "input[type=text]", function() {
        var input_id = $(this).attr('id');
        var input_val = $(this).val();
        var select_id = $(this).closest("tr").find('select').attr('id');
        
        var index = input_id.lastIndexOf("-");
        var type = input_id.substring(index + 1);
        
        pushHtml(type, select_id, input_val);
    });
    
    function pushHtml(type, select_id, input_val){
        if (type == 'percentwidth'){
            $('.div-layout .' + select_id).css("width", input_val + '%');
        }else if(type == 'percentheight'){
            $('.div-layout .' + select_id).css("height", input_val + '%');
        }else if(type == 'margintop'){
            $('.div-layout .' + select_id).css("top", input_val + '%');
        }else if(type == 'marginleft'){
            $('.div-layout .' + select_id).css("left", input_val + '%');
        }
    }
    
    //remove when delete
    $('.list_frame').on('afterDeleteRow', function(e, row) {
        var id_case = row.find('select').attr('id');
        $( ".div-layout " ).find('.' + id_case).remove();
    });

    function ajaxGetFrame(url, frame_id, classSelect, percentWidth, percentHeight, marginTop, marginLeft, backgroundcolor) {
        $.ajax({
            type: "POST",
            url: url,
            cache: true,
            data: {
                frame_id: frame_id,
                classSelect: classSelect
            },
            success: function (data) {
                //substring
                var index = data.indexOf("<");
                var frame_params = data.substring(0, index);
                var frame_html = data.substring(index);
                
                frame_params = JSON.parse(frame_params);
 
                $( ".div-layout" ).append( frame_html );
                $( percentWidth ).val( frame_params[0] );
                $( percentHeight ).val( frame_params[1] );
                $( marginTop ).val( frame_params[2] );
                $( marginLeft ).val( frame_params[3] );
            }
        });
    }
    
    
JS;

$this->registerJs($script);
?>