<?php

/**



 * This is the customized model class for table "SmartscreenFrame".
 */

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use kartik\widgets\ColorInput;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenFrame';
$moduleTitle = 'Smartscreen Frame';
$moduleKey = 'smartscreen-frame';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenFrame */
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
    'id' => 'smartscreen-frame-form',
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
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            //'content_id' => ['value' => $form->fieldNoLabel($model, 'content_id')->select(FHtml::getComboArray('@smartscreen_content'))],

                                            //'content' => ['value' => $form->fieldNoLabel($model, 'content')->html()],
                                            //'file' => ['value' => $form->fieldNoLabel($model, 'file')->file()],


                                            'contentLayout' => ['value' => $form->fieldNoLabel($model, 'contentLayout')->select()],
                                            'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->boolean()],

                                        ]]); ?>
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => 'Position',  'form' => $form, 'columns' => 2, 'attributes' => [

                                            'marginTop' => ['value' => $form->fieldNoLabel($model, 'marginTop')->numeric()],
                                            'marginLeft' => ['value' => $form->fieldNoLabel($model, 'marginLeft')->numeric()],
                                            'percentWidth' => ['value' => $form->fieldNoLabel($model, 'percentWidth')->numeric()],
                                            'percentHeight' => ['value' => $form->fieldNoLabel($model, 'percentHeight')->numeric()],
                                            'backgroundColor' => ['value' => $form->fieldNoLabel($model, 'backgroundColor')->widget(ColorInput::classname(), [
                                                'options' => [
                                                    'placeholder' => 'Select color ...',
                                                ],
                                            ])],
                                            'font_color' => ['value' => $form->fieldNoLabel($model, 'font_color')->widget(ColorInput::classname(), [
                                                'options' => [
                                                    'placeholder' => 'Select color ...',
                                                ],
                                            ])],
                                        ]]); ?>


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
                <?= \backend\modules\smartscreen\Smartscreen::showFramePreview($model) ?>
            </div>
        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>


<?php
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();

$script = <<< JS
    var url = '$baseUrl' + '/index.php/smartscreen/smartscreen-schedules/get-content';

    $("#smartscreenframe-percentwidth").keyup(function(){
        var width = $(this).val();
        
        if (width > 0) {
            width = $(this).val() + '%';
        }else{
            width = 0;
        }

        $(".div-form").css("width", width);
    });
    
    $("#smartscreenframe-percentheight").keyup(function(){
        var height = $(this).val();
        
        if (height > 0) {
            height = $(this).val() + '%';
        }else{
            height = 0;
        }

        $(".div-form").css("height", height);
    });
    
    $("#smartscreenframe-margintop").keyup(function(){
        var marginTop = $(this).val();
        
        if (marginTop > 0) {
            marginTop = $(this).val() + '%';
        }else{
            marginTop = 0;
        }

        $(".div-form").css("top", marginTop);
    });
    
    $("#smartscreenframe-marginleft").keyup(function(){
        var marginLeft = $(this).val();
        
        if (marginLeft > 0) {
            marginLeft = $(this).val() + '%';
        }else{
            marginLeft = 0;
        }

        $(".div-form").css("left", marginLeft);
    });
    
    $("#w0").on("change", "#smartscreenframe-backgroundcolor", function(){
        setColor();
    });
    
    function setColor(){
        var color = $('#smartscreenframe-backgroundcolor').val();
        
        if (color.length > 0){
            $(".div-form").css("backgroundColor", color);
        }else{
            $(".div-form").css("backgroundColor", "transparent");
        }
    }
    
JS;

$this->registerJs($script);
?>