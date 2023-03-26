<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;

use common\widgets\FCKEditor;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\slider\Slider;
?>

<?php $form = ActiveForm::begin([
'id' => 'app-user-feedback-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => !$canEdit, // check the Role here
'readonly' => !$canEdit, // check the Role here
'options' => [
//'class' => 'form-horizontal',
'enctype' => 'multipart/form-data'
]
]);
 ?>


       <?=  //name: user_id, comment: lookup:@user, dbType: varchar(100), phpType: string, size: 100, allowNull:
        //$form->field($model, 'user_id')->dropDownList(FHtml::getComboArray('@user', 'user', 'user_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'user_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@user', 'user', 'user_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: object_id, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_id')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: comment, comment: , dbType: varchar(4000), phpType: string, size: 4000, allowNull:  
        $form->field($model, 'comment')->widget(FCKEditor::className(), ['options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'])  ?>

       <?=  //name: response, comment: , dbType: text, phpType: string, size: , allowNull: 1 
        $form->field($model, 'response')->textarea(['rows' => 3]) ?>

       <?=  //name: type, comment: data:Question,Feedback,Report, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:New,Received,Processing,Pending,Closed, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_feedback', 'app_user_feedback', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>


   <?php ActiveForm::end(); ?>



