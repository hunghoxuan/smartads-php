<?php

/**



 * This is the customized model class for table "SettingsText".
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
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

$lang_array = FHtml::applicationLangsArray();
$lang_values = [];
foreach ($lang_array as $lang => $lang_name) {
    $lang_values = array_merge($lang_values, [$lang => FHtml::t($model->name, false, [], $lang)]);
}


/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\SettingsText */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?php $form = FActiveForm::begin([
    'id' => 'settings-text-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => $edit_type,
    'display_type' => $display_type,
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

        <div class="col-md-12">
            <div class="portlet light">

                <div class="portlet-title hidden-print">
                    <div class="caption-title uppercase font-dark">
                        <div class="col-md-12">
                            <?php echo $model->name ?>
                        </div>
                    </div>
                    <div class="tools">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">

                                        <?= //name: metaKey, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
                                        $form->field($model, 'name')->textInput(['readonly' => !$model->isNewRecord]) ?>
                                        <hr />

                                        <?php
                                        foreach ($lang_array as $lang => $name) {
                                            echo $form->field($model, "_$lang")->label($name)->textarea(['value' => $lang_values[$lang]]);
                                        } ?>

                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, false, '{save}{cancel}') : FHtml::showActionsButton($model, $canEdit, false, '{save}{cancel}') ?>

        </div>

    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>