<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\slider\Slider;
use common\widgets\FActiveForm;


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
<?php if (Yii::$app->request->isAjax) { ?>

<?php $form = FActiveForm::begin(
['id' => 'app-user-transaction-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => false, // check the Role here
'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
]]); ?>

<input type="hidden" id="saveType" name="saveType">

       <?=  //name: id, comment: , dbType: bigint(20), phpType: string, size: 20, allowNull:  
        //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: type, comment: data:PAYMENT,DEPOSIT,FEE,WITHDRAW,BONUS,REFUND,REDEEM, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:PENDING=0,APPROVED=1,REJECTED=-1, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

   <?php FActiveForm::end(); ?>


<?php } else { ?>

<div class="app-user-transaction-form">
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="<?php  echo $this->params['mainIcon'] ?>"></i>
                <span class="caption-subject font-blue-madison bold uppercase">
                    <?= FHtml::t('common', $moduleTitle) ?>                </span>
            </div>
            <div class="tools">
                <a href="#" class="fullscreen"></a>
                <a href="#" class="collapse"></a>
            </div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body form">
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
                <div class="form-body">

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

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: type, comment: data:PAYMENT,DEPOSIT,FEE,WITHDRAW,BONUS,REFUND,REDEEM, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:PENDING=0,APPROVED=1,REJECTED=-1, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                </div>


            </div>
               <?php FActiveForm::end(); ?>
        </div>

    </div>
</div>
<?php
$display_actions = !isset($display_actions) ? true : false;
if ($display_actions)
echo $this->render('_detail_actions', ['model' => $model, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
  ?><?php } ?>


