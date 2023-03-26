<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsLookup".
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

$moduleName = 'SettingsLookup';
$moduleTitle = 'Settings Lookup';
$moduleKey = 'settings-lookup';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsLookup */
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
'id' => 'settings-lookup-form',
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
                                <?= FHtml::showField('Created', FHtml::getFieldValue($model, 'created_date'), FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                <?= FHtml::showField(' ', $model->created_user, FHtml::SHOW_USER) ?>                                </div>
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
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                                   <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: params, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'params')->textarea(['rows' => 3]) ?>

       <?=  //name: fields, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'fields')->textarea(['rows' => 3]) ?>

       <?=  //name: orderby, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'orderby')->textInput() ?>

       <?=  //name: limit, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'limit')->textInput() ?>

       <?=  //name: sql, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'sql')->textarea(['rows' => 3]) ?>

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
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                           <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: is_cached, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_cached')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_cached')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_active, comment: , dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

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
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                -->
                
                
                <?php                $type = FHtml::getFieldValue($model, 'type');
                if (isset($modelMeta) && !empty($type))
                    echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
                  ?>
                <?php                    $display_actions = !isset($display_actions) ? true : false;
                    if ($display_actions)
                        echo $this->render('_detail_actions', ['model' => $model, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
                  ?>
            </div>
    </div>
</div>
   <?php FActiveForm::end(); ?>
