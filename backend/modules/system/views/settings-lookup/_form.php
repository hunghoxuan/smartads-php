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
<?php if (Yii::$app->request->isAjax) { ?>

<?php $form = FActiveForm::begin(
['id' => 'settings-lookup-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => false, // check the Role here
'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
]]); ?>

<input type="hidden" id="saveType" name="saveType">

       <?=  //name: id, comment: , dbType: int(11), phpType: integer, size: 11, allowNull:  
        //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('settings_lookup', 'settings_lookup', 'id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_lookup', 'settings_lookup', 'id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: is_cached, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_cached')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_cached')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_active, comment: , dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: sort_order, comment: , dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        $form->field($model, 'sort_order')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

   <?php FActiveForm::end(); ?>


<?php } else { ?>

<div class="settings-lookup-form">
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
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
                <div class="form-body">

                           <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

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

       <?=  //name: is_cached, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_cached')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_cached')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_active, comment: , dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: sort_order, comment: , dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        $form->field($model, 'sort_order')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

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


