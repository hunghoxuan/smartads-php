<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsSchema".
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

$moduleName = 'SettingsSchema';
$moduleTitle = 'Settings Schema';
$moduleKey = 'settings-schema';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsSchema */
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
'id' => 'settings-schema-form',
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

       <?=  //name: description, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

       <?=  //name: dbType, comment: data:numeric,bool,float,varchar,text,date,time,datetime, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'dbType')->dropDownList(FHtml::getComboArray('settings_schema', 'settings_schema', 'dbType', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'dbType')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_schema', 'settings_schema', 'dbType', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: editor, comment: data:text,textarea,select,numeric,currency,boolean,date,time,datetime,range,file,image, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'editor')->dropDownList(FHtml::getComboArray('settings_schema', 'settings_schema', 'editor', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'editor')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_schema', 'settings_schema', 'editor', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: lookup, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'lookup')->textInput() ?>

       <?=  //name: format, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'format')->textInput() ?>

       <?=  //name: algorithm, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'algorithm')->textInput() ?>

       <?=  //name: group, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'group')->textInput() ?>

          <?=  //name: roles, comment: lookup:@roles, dbType: varchar(500), phpType: string, size: 500, allowNull: 1
        //$form->field($model, 'roles')->dropDownList(FHtml::getComboArray('@roles', 'roles', 'roles', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'roles')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('role', 'roles', 'roles', true, 'id', 'name'), 'options'=>['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

                                <div class="portlet light">
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
                                           <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('settings_schema', 'settings_schema', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_schema', 'settings_schema', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: is_active, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_system, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_system')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_system')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_readonly, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_readonly')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_readonly')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                    <?php if ($canEdit) { echo                     FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                    echo '  ' . FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']); } ?>
                    <?php  if (!$model->isNewRecord && $canDelete) {?>
                    <?=  FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                    'confirm' => FHtml::t('common', 'Are you sure to delete?'),
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




