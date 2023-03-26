<?php
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

$moduleName = 'AppUserFeedback';
$moduleTitle = 'App User Feedback';
$moduleKey = 'app-user-feedback';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$form_layout = FHtml::LAYOUT_NEWLINE;
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserFeedback */
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
'id' => 'app-user-feedback-form',
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
        <div class="profile-sidebar col-md-3">
            <div class="portlet light">
                                                                <div class="margin-top-20">&nbsp;
                                                                            </div>
                <div class="margin-top-20">
                                    </div>
                <div class="margin-top-20">
                    <?= FHtml::showLabel('app_user_feedback.type', 'app_user_feedback', 'type', $model->type) ?>
<?= FHtml::showLabel('app_user_feedback.status', 'app_user_feedback', 'status', $model->status) ?>
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
                            <?= FHtml::showField('Created', FHtml::getFieldValue($model, 'created_date'), FHtml::SHOW_DATE) ?>                            </div>
                        <div class="col-md-6">
                            <?= FHtml::showField(' ', $model->created_user, FHtml::SHOW_USER) ?>                            </div>
                    </div>
                    <div class="row list-separated profile-stat">
                        <div class="col-md-6">
                            <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                            </div>
                        <div class="col-md-6">
                            <?= FHtml::showField(' ', $model->modified_user, FHtml::SHOW_USER) ?>                            </div>
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
                        <li>
                            <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Group')?></a>
                        </li><li>
                                <a href="#tab_1_5" data-toggle="tab"><?= FHtml::t('common', 'Object')?></a>
                                </li>
                            <li>
                                <a href="#tab_1_6" data-toggle="tab"><?= FHtml::t('common', 'Application')?></a>
                                </li>
                             </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: user_id, comment: lookup:@user, dbType: varchar(100), phpType: string, size: 100, allowNull:
        //$form->field($model, 'user_id')->dropDownList(FHtml::getComboArray('@user', 'user', 'user_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'user_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@user', 'user', 'user_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: comment, comment: , dbType: varchar(4000), phpType: string, size: 4000, allowNull:  
        $form->field($model, 'comment')->widget(FCKEditor::className(), ['options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'])  ?>

       <?=  //name: response, comment: , dbType: text, phpType: string, size: , allowNull: 1 
        $form->field($model, 'response')->textarea(['rows' => 3]) ?>

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

                                <div class="tab-pane row" id="tab_1_4">
                                    <div class="col-md-12">
                                               <?=  //name: type, comment: data:Question,Feedback,Report, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:New,Received,Processing,Pending,Closed, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                                    </div>
                                </div>
                                                                        <div class="tab-pane row" id="tab_1_5">
                                            <div class="col-md-12">
                                                       <?=  //name: object_id, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_id')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                                            </div>
                                        </div>
                                                                                <div class="tab-pane row" id="tab_1_6">
                                            <div class="col-md-12">
                                                                                            </div>
                                        </div>
                                        
                                                                <!--<div class="tab-pane row" id="tab_1_p">
                                    <div class="col-md-12">
                                                                            </div>
                                </div>
                                -->                            </div>
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
                <?php if ($canEdit) { echo                 FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                echo '  ' . FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FFHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']); } ?>
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
   <?php FActiveForm::end(); ?>




