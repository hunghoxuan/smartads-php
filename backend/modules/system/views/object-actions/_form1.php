<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ObjectActions".
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

$form_Type = $this->params['activeForm_type'];

$moduleName = 'ObjectActions';
$moduleTitle = 'Object Actions';
$moduleKey = 'object-actions';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectActions */
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


<?php $form = FActiveForm::begin([
'id' => 'object-actions-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => !$canEdit, // check the Role here
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
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?> : <?= FHtml::showObjectConfigLink($model) ?>                        </span>
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
                                               <?= $form->field($model, 'object_id')->select(FHtml::getComboArray('object_actions', 'object_actions', 'object_id', true, 'id', 'name')) ?>

       <?= $form->field($model, 'name')->textarea(['rows' => 3]) ?>

       <?= $form->field($model, 'old_content')->textarea(['rows' => 3]) ?>

       <?= $form->field($model, 'content')->html()  ?>

       <?= $form->field($model, 'action')->select(FHtml::getComboArray('object_actions', 'object_actions', 'action', true, 'id', 'name')) ?>


                                        <!--
                                        
                                        -->


                                               <?= FHtml::showGroupHeader('Groups') ?>                                        <div class="row">
                                            <div class="col-md-6">
                                               <?= $form->field($model, 'object_type')->select(FHtml::getComboArray('object_actions', 'object_actions', 'object_type', true, 'id', 'name'))->labelSpan(6) ?>

                                            </div>
                                            <div class="col-md-6">
                                                       <?= $form->field($model, 'is_active')->checkbox() ->labelSpan(6) ?>

                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        
                                        <?= FormObjectFile::widget( [
                                        'model' => $model, 'form' => $form,
                                        'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => 'object-file'
                                        ]) ?>
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?= FormObjectAttributes::widget( [
                                        'model' => $model, 'form' => $form,
                                        'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
                                        ]) ?>
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
            <script language="javascript" type="text/javascript">
                function submitForm($saveType) {
                    $('#saveType').val($saveType);
                }
            </script>

            <?php if (Yii::$app->request->isAjax) { ?>

            <input type="hidden" id="saveType" name="saveType">

            <?php } else { ?>
            <input type="hidden" id="saveType" name="saveType">

            <?=      FHtml::showActionsButton($model, $canEdit, $canDelete)  ?>
            <?php } ?>
        </div>
        <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">
            <div class="portlet light">
                <?= FHtml::showModelPreview($model) ?>
            </div>
            <div class="row" style="padding-left:35px; color:grey">
                <?=  FHtml::showModelHistory($model) ?>
            </div>
        </div>
    </div>
</div>
   <?php FActiveForm::end(); ?>
