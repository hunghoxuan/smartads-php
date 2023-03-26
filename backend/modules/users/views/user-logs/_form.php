<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "UserLogs".
*/
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
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
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'UserLogs';
$moduleTitle = 'User Logs';
$moduleKey = 'user-logs';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\UserLogs */
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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php $form = FActiveForm::begin([
'id' => 'user-logs-form',
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
                    <?= (FHtml::isViewAction($currentAction)) ?  FHtml::showPrintHeader($moduleName) : ''  ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?> : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?>                        </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info')?></a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads')?></a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes')?></a>
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
                                        'log_date' => ['value' => $form->fieldNoLabel($model, 'log_date')->date(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'user_id' => ['value' => $form->fieldNoLabel($model, 'user_id')->select(FHtml::getComboArray('@app_user', 'app_user', 'user_id', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'action' => ['value' => $form->fieldNoLabel($model, 'action')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'object_id' => ['value' => $form->fieldNoLabel($model, 'object_id')->select(FHtml::getComboArray('user_logs', 'user_logs', 'object_id', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'link_url' => ['value' => $form->fieldNoLabel($model, 'link_url')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'ip_address' => ['value' => $form->fieldNoLabel($model, 'ip_address')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'duration' => ['value' => $form->fieldNoLabel($model, 'duration')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'note' => ['value' => $form->fieldNoLabel($model, 'note')->html() , 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                              ]]); ?>


                                        <!--
                                        
                                        -->


                                               <?php echo FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Group'), 'form' => $form, 'columns' => 2, 'attributes' => [ 

                                            'object_type' => ['value' => $form->fieldNoLabel($model, 'object_type')->selectMany(FHtml::getComboArray('user_logs', 'user_logs', 'object_type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'status' => ['value' => $form->fieldNoLabel($model, 'status')->selectMany(FHtml::getComboArray('user_logs', 'user_logs', 'status', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                                
                                              ]]); ?>



                                        
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                               <?php echo FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                        
                                              ]]); ?>

                                        <hr/>
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
                                
                                <!--<div class="tab-pane row" id="tab_1_p">
                                    <div class="col-md-12">
                                                                            </div>
                                </div>
                                -->

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            
            <?php            $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
              ?>
            <?= (FHtml::isViewAction($currentAction)) ?  FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete)  ?>

        </div>
        <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">
            <div class="portlet light">
                <?= FHtml::showModelPreview($model) ?>
            </div>
        </div>
    </div>
</div>
   <?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end()  ?>
