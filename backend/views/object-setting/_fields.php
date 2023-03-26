<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;


?>

<?php $form = \common\widgets\FActiveForm::begin([
    'id' => 'object-setting-form',
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


<?= //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_setting.object_type', 'object_setting', 'object_type', true, 'id', 'name'), ['prompt' => '']) ?>

<?= //name: meta_key, dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

<?= //name: key, dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'key')->textInput(['maxlength' => true]) ?>

<?= //name: value, dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'value')->textInput(['maxlength' => true]) ?>

<?= //name: description, dbType: text, phpType: string, size: , allowNull: 1
$form->field($model, 'description')->textarea(['rows' => 3]) ?>

<?= //name: icon, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
$form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

<?= //name: color, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
$form->field($model, 'color')->widget(\kartik\widgets\ColorInput::className(), ['pluginOptions' => []]) ?>

<?= //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
$form->field($model, 'is_active')->widget(\kartik\widgets\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

<?= //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:
$form->field($model, 'sort_order')->widget(\kartik\widgets\TouchSpin::className(), ['pluginOptions' => ['initval' => 1, 'min' => 0, 'max' => 10000000, 'step' => 1, 'decimals' => 0, 'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus', 'prefix' => '', 'postfix' => '']]) ?>

<?= //name: application_id, dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'application_id')->textInput(['maxlength' => true]) ?>


<?php \common\widgets\FActiveForm::end(); ?>



