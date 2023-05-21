<?php

/**



 * This is the customized model class for table "User".
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

$moduleName = 'User';
$moduleTitle = 'User';
$moduleKey = 'user';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\User */
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
    'id' => 'user-form',
    'type' => ActiveForm::TYPE_HORIZONTAL, //ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
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

        <div class="col-md-8">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ? FHtml::showPrintHeader($moduleName) : '' ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>
                            : <?= FHtml::showObjectConfigLink($model) ?>
                        </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Personal') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Passwords & Tokens') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Roles & Permissions') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],

                                            'code' => ['value' => $form->fieldNoLabel($model, 'code')->textInput()],
                                            'email' => ['value' => $form->fieldNoLabel($model, 'email')->emailInput(), 'columnOptions' => ['colspan' => 3]],
                                            'overview' => ['value' => $form->fieldNoLabel($model, 'overview')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 3]],
                                            'content' => ['value' => $form->fieldNoLabel($model, 'content')->html(), 'columnOptions' => ['colspan' => 3]],

                                        ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [
                                            'status' => ['value' => $form->fieldNoLabel($model, 'status')->select()],
                                            'type' => ['value' => $form->fieldNoLabel($model, 'type')->select()],
                                        ]]); ?>
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [
                                            'image' => ['value' => $form->fieldNoLabel($model, 'image')->image(), 'columnOptions' => ['colspan' => 3]],

                                            'phone' => ['value' => $form->fieldNoLabel($model, 'phone')->textInput()],
                                            'skype' => ['value' => $form->fieldNoLabel($model, 'skype')->textInput()],

                                            'identity_card' => ['value' => $form->fieldNoLabel($model, 'identity_card')->textInput()],
                                            'gender' => ['value' => $form->fieldNoLabel($model, 'gender')->select()],

                                            'birth_date' => ['value' => $form->fieldNoLabel($model, 'birth_date')->date()],
                                            'birth_place' => ['value' => $form->fieldNoLabel($model, 'birth_place')->textInput()],
                                            'country' => ['value' => $form->fieldNoLabel($model, 'country')->select()],
                                            'city' => ['value' => $form->fieldNoLabel($model, 'city')->select()],
                                            'state' => ['value' => $form->fieldNoLabel($model, 'state')->select()],
                                            'post_code' => ['value' => $form->fieldNoLabel($model, 'post_code')->textInput()],

                                            'address' => ['value' => $form->fieldNoLabel($model, 'address')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 3]],

                                        ]]); ?>
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => FHtml::t('common', 'Employment'), 'attributes' => [
                                            'organization' => ['value' => $form->fieldNoLabel($model, 'organization')->select()],
                                            'department' => ['value' => $form->fieldNoLabel($model, 'department')->select()],
                                            'position' => ['value' => $form->fieldNoLabel($model, 'position')->select()],
                                            'type' => ['value' => $form->fieldNoLabel($model, 'type')->select()],

                                            'start_date' => ['value' => $form->fieldNoLabel($model, 'start_date')->date()],
                                            'end_date' => ['value' => $form->fieldNoLabel($model, 'end_date')->date()],

                                        ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => FHtml::t('common', 'Finance'), 'attributes' => [
                                            'card_number' => ['value' => $form->fieldNoLabel($model, 'card_number')->textInput()],
                                            'card_name' => ['value' => $form->fieldNoLabel($model, 'card_name')->textInput()],
                                            'card_exp' => ['value' => $form->fieldNoLabel($model, 'card_exp')->textInput()],
                                            'card_cvv' => ['value' => $form->fieldNoLabel($model, 'card_cvv')->textInput()],

                                            'balance' => ['value' => $form->fieldNoLabel($model, 'balance')->numeric()],
                                            'point' => ['value' => $form->fieldNoLabel($model, 'point')->numeric()],

                                        ]]); ?>

                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?=  //name: auth_key, dbType: varchar(32), phpType: string, size: 32, allowNull:
                                        $form->field($model, 'username')->textInput(['readonly' => !$model->isNewRecord]) ?>
                                        <?= FHtml::isRoleAdmin() ? $form->field($model, 'password_new')->passwordInput(['readonly' => false])->hint('Reset New Password here') : '' ?>

                                        <?=  //name: auth_key, dbType: varchar(32), phpType: string, size: 32, allowNull:
                                        $form->field($model, 'auth_key')->textInput(['readonly' => true]) ?>
                                        <?=  //name: password_hash, dbType: varchar(255), phpType: string, size: 255, allowNull:
                                        $form->field($model, 'password_hash')->hiddenInput(['readonly' => true]) ?>
                                        <?=  //name: password_reset_token, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                                        $form->field($model, 'password_reset_token')->textInput(['readonly' => true]) ?>
                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_4">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'groups_array')->selectMany(FHtml::getApplicationGroupsComboArray()) ?>
                                        <?= $form->field($model, 'rights_array')->selectMany(FHtml::getApplicationRolesComboArray()) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?php $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
            ?>
            <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>

        </div>
        <div class="profile-sidebar col-md-4 col-xs-12 hidden-print">
            <div class="portlet light">
                <?= FHtml::showModelPreview($model, ['username'], ['name', 'dob', 'email', 'phone:text'], ['image', 'avatar'], ['lat', 'long', 'rate', 'rate_count', 'status', 'is_online', 'created_date', 'last_login:date']) ?>
            </div>

        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>