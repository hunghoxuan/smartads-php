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
'id' => 'settings-schema-form',
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


       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('settings_schema', 'settings_schema', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_schema', 'settings_schema', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: grid_size, comment: data:hidden,small,normal,large,col-1,col-2,col-3,col-4,col-5, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'grid_size')->dropDownList(FHtml::getComboArray('settings_schema', 'settings_schema', 'grid_size', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'grid_size')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_schema', 'settings_schema', 'grid_size', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: roles, comment: lookup:@roles, dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
        //$form->field($model, 'roles')->dropDownList(FHtml::getComboArray('@roles', 'roles', 'roles', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'roles')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@roles', 'roles', 'roles', true, 'id', 'name'), 'options'=>['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: sort_order, comment: , dbType: int(5), phpType: integer, size: 5, allowNull: 1 
        $form->field($model, 'sort_order')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

       <?=  //name: is_active, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_system, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_system')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_system')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_readonly, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_readonly')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_readonly')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>


   <?php \common\widgets\FActiveForm::end(); ?>



