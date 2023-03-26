<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppUserTransaction".
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

$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserTransaction */
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
'id' => 'app-user-transaction-form',
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
                                                                                            </div>
                    <div class="margin-top-20">
                                            </div>
                    <div class="margin-top-20">
                        <?= FHtml::showLabel('app_user_transaction', 'app_user_transaction', 'type', $model->type) ?>
<?= FHtml::showLabel('app_user_transaction', 'app_user_transaction', 'status', $model->status) ?>
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
                                <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                <?= FHtml::showField(' ', $model->modified_user, FHtml::SHOW_USER) ?>                                </div>
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
                                                   <?=  //name: transaction_id, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        //$form->field($model, 'transaction_id')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'transaction_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'transaction_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'transaction_id', true, 'id', 'name'), 'options'=>['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: user_id, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'user_id')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'user_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'user_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'user_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: receiver_user_id, comment: lookup:@user, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'receiver_user_id')->dropDownList(FHtml::getComboArray('@user', 'user', 'receiver_user_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'receiver_user_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@user', 'user', 'receiver_user_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: object_id, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_id')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: amount, comment: , dbType: decimal(20,2), phpType: string, size: 20, allowNull:  
        $form->field($model, 'amount')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: currency, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'currency')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'currency', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'currency')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'currency', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: payment_method, comment: data:POINT,CREDIT,CASH,BANK,PAYPAL,WU, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'payment_method')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'payment_method', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'payment_method')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'payment_method', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: note, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'note')->widget(FCKEditor::className(), ['options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'])  ?>

       <?=  //name: time, comment: , dbType: varchar(20), phpType: string, size: 20, allowNull:  
        $form->field($model, 'time')->widget(\kartik\widgets\DatePicker::className(), ['pluginOptions' => ['format' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'dd M yyyy'), 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true ]])
 ?>

       <?=  //name: action, comment: data:SYSTEM_ADJUST,CANCELLATION_ORDER_FEE,EXCHANGE_POINT,REDEEM_POINT,TRANSFER_POINT,TRIP_PAYMENT,PASSENGER_SHARE_BONUS,DRIVER_SHARE_BONUS, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'action')->textInput() ?>

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
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: type, comment: data:PAYMENT,DEPOSIT,FEE,WITHDRAW,BONUS,REFUND,REDEEM, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:PENDING=0,APPROVED=1,REJECTED=-1, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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
