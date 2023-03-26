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
'id' => 'settings-text-form',
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


       <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: description, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

       <?=  //name: description_en, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_en')->textarea(['rows' => 3]) ?>

       <?=  //name: description_es, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_es')->textarea(['rows' => 3]) ?>

       <?=  //name: description_pt, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_pt')->textarea(['rows' => 3]) ?>

       <?=  //name: description_de, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_de')->textarea(['rows' => 3]) ?>

       <?=  //name: description_fr, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_fr')->textarea(['rows' => 3]) ?>

       <?=  //name: description_it, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_it')->textarea(['rows' => 3]) ?>

       <?=  //name: description_ko, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_ko')->textarea(['rows' => 3]) ?>

       <?=  //name: description_ja, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_ja')->textarea(['rows' => 3]) ?>

       <?=  //name: description_vi, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_vi')->textarea(['rows' => 3]) ?>

       <?=  //name: description_zh, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'description_zh')->textarea(['rows' => 3]) ?>


   <?php \common\widgets\FActiveForm::end(); ?>



