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

<?php $form = \common\widgets\FActiveForm::begin([
'id' => 'app-user-pro-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => !$canEdit, // check the Role here
'options' => [
//'class' => 'form-horizontal',
'enctype' => 'multipart/form-data'
]
]);
 ?>


       <?=  //name: rate, comment: , dbType: float(3,1), phpType: double, size: 3, allowNull: 1 
        $form->field($model, 'rate')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: rate_count, comment: , dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        $form->field($model, 'rate_count')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

       <?=  //name: description, comment: , dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
        //$form->field($model, 'description')->dropDownList(FHtml::getComboArray('app_user_pro', 'app_user_pro', 'description', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'description')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_pro', 'app_user_pro', 'description', true, 'id', 'name'), 'options'=>['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: business_name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'business_name')->textInput() ?>

       <?=  //name: business_email, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'business_email')->input('email') ?>

       <?=  //name: business_address, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'business_address')->textInput() ?>

       <?=  //name: business_website, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'business_website')->textInput() ?>

       <?=  //name: business_phone, comment: , dbType: varchar(20), phpType: string, size: 20, allowNull: 1 
        //$form->field($model, 'business_phone')->dropDownList(FHtml::getComboArray('app_user_pro', 'app_user_pro', 'business_phone', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'business_phone')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user_pro', 'app_user_pro', 'business_phone', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: is_active, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>


   <?php \common\widgets\FActiveForm::end(); ?>



