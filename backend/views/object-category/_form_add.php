<?php

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole($model, 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = isset($canDelete) ? $canDelete :FHtml::isInRole($model, 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : '_form_add');

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */
/* @var $form common\widgets\FActiveForm */
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
    'id' => 'object-category-form',
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
]); ?>

    <div class="form">
        <div class="row">
            <div class="col-md-12">

                                            <?= FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                                'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                                'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3])],
                                                'image' => ['value' => $form->fieldNoLabel($model, 'image')->image()],
                                                'object_type' => ['value' => $form->fieldNoLabel($model, 'object_type')->textInput()],

                                            ]]); ?>
                                            <?php /*
;
                                            */ ?>

            </div>
            <div class="col-md-12">
                <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete, $ajax) : FHtml::showActionsButton($model, $canEdit, $canDelete, $ajax) ?>
            </div>
        </div>
    </div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>