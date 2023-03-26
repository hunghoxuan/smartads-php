<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsText".
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

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsText */
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


<?php $form = \common\widgets\FActiveForm::begin([
'id' => 'settings-text-form',
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
            <div class="profile-sidebar col-md-3">
                <div class="portlet light">
                                         <div class="margin-top-20">&nbsp;
                        <h4><b><?= $model->name  ?></b></h4>
                                                        <small class='text-default'><?= $model->description  ?></small>
                    </div>
                    <div class="margin-top-20">
                                            </div>
                    <div class="margin-top-20">
                                            </div>

                    <!--
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="#">
                                    <i class="icon-settings"></i> Edit Detail </a>
                            </li>
                        </ul>
                    </div>-->
                </div>
                <!-- END MENU -->
                <div class="portlet light">
                     <div class="row list-separated profile-stat">
                         </div>
                    <div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                                                </div>
                            <div class="col-md-6">
                                                                </div>
                        </div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                                                </div>
                            <div class="col-md-6">
                                                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
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
                    <div class="body">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                                   <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: description, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description')->textarea(['rows' => 1]) ?>



       <?=  //name: description_es, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_es')->textarea(['rows' => 1]) ?>

       <?=  //name: description_pt, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_pt')->textarea(['rows' => 1]) ?>

       <?=  //name: description_de, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_de')->textarea(['rows' => 1]) ?>

       <?=  //name: description_fr, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_fr')->textarea(['rows' => 1]) ?>

       <?=  //name: description_it, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_it')->textarea(['rows' => 1]) ?>

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

                <!--                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Grouping')?></span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <!--
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Pricing')?></span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                -->
                <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'ASIA')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: description_ko, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_ko')->textarea(['rows' => 1]) ?>

       <?=  //name: description_ja, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_ja')->textarea(['rows' => 1]) ?>

       <?=  //name: description_vi, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_vi')->textarea(['rows' => 1]) ?>

       <?=  //name: description_zh, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_zh')->textarea(['rows' => 1]) ?>


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

                <div class="">
                    <?php if ($canEdit) { echo                     Html::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                    echo '  ' . Html::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']); } ?>
                    <?php  if (!$model->isNewRecord && $canDelete) {?>
                    <?=  FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                    'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                    'method' => 'post',
                    ],
                    ]); ?>
                    <?php }  ?>
                    <?=  ' | ' . FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                </div>
                <?php } ?>
            </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




