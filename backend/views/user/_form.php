<?php

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "User".
 */

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'User';
$moduleTitle = 'User';
$moduleKey = 'user';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = !FHtml::isViewAction($currentAction) && (FHtml::isInRole('', FHtml::currentAction(), $currentRole) || (FHtml::getFieldValue($model, ['id', 'user_id']) == FHtml::currentUserId()));
$canDelete = FHtml::isRoleAdmin() ? true : false;

$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
if (empty($model->role))
    $model->role = FHtml::ROLE_USER;

$is_admin = FHtml::isRoleAdmin() && FHtml::currentUserId() != $model->id && $model->role < FHtml::getCurrentRole();

$size = Yii::$app->request->isAjax ? 12 : 9;

//$controllers = FHtml::getModuleControllers('purchase', true);
//FHtml::var_dump($controllers); die;

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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable-pjax']) ?>

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

        <div class="col-md-<?= $size ?>">
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
                        <?php if (!Yii::$app->request->isAjax) { ?>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Personal') ?></a>
                            </li>
                            <?php if ($canEdit) { ?>

                                <li>
                                    <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Reset Password') ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($is_admin) { ?>
                                <li>
                                    <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Roles & Permissions') ?></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            '_username' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, '_username')->textInput(['readonly' => !$model->isNewRecord])],

                                            'name' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            'email' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'email')->emailInput()],
                                            'image' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'image')->image()],

                                            'status' => ['type' => FHtml::INPUT_RAW, 'readonly' => !$is_admin, 'value' => $form->fieldNoLabel($model, 'status')->select('user.status')],
                                        ]]); ?>

                                    </div>
                                </div>
                                <?php if (!Yii::$app->request->isAjax) { ?>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">

                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [
                                                'code' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'code')->textInput(), 'columnOptions' => ['colspan' => 3]],
                                                'overview' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'overview')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 3]],
                                                'address' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'address')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 3]],

                                                'phone' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'phone')->textInput()],

                                                'skype' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'skype')->textInput()],

                                                'identity_card' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'identity_card')->textInput()],
                                                'gender' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'gender')->select()],

                                                'birth_date' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'birth_date')->date()],
                                                'birth_place' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'birth_place')->textInput()],
                                                'country' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'country')->select()],
                                                'city' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'city')->select()],

                                            ]]); ?>
                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => FHtml::t('common', 'Employment'), 'attributes' => [
                                                'organization' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'organization')->select()],
                                                'department' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'department')->select()],
                                                'position' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'position')->select()],
                                                'type' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'type')->select()],
                                                'start_date' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'start_date')->date()],
                                                'end_date' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'end_date')->date()],
                                                'content' => ['type' => FHtml::INPUT_RAW, 'value' => $form->fieldNoLabel($model, 'content')->textArea(), 'columnOptions' => ['colspan' => 3]],

                                            ]]); ?>

                                        </div>
                                    </div>
                                    <?php if ($canEdit) { ?>
                                        <div class="tab-pane row" id="tab_1_3">
                                            <div class="col-md-12">
                                                <?= (FHtml::isRoleAdmin() || FHtml::currentUserId() == $model->id)  ? $form->field($model, 'password_new')->passwordInput(['readonly' => false])->hint('') : '' ?>
                                                <?= (FHtml::isRoleAdmin() || FHtml::currentUserId() == $model->id)  ? $form->field($model, 'password_retype')->passwordInput(['readonly' => false])->hint('Retype Password') : '' ?>

                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($is_admin) { ?>

                                        <div class="tab-pane row" id="tab_1_4">

                                            <div class="col-md-12">
                                                <?= $form->field($model, 'role', ['display_type' => FActiveForm::TYPE_HORIZONTAL])->select()->onChange([FHtml::ROLE_USER => 'div_user_role_10']) ?>
                                                <div id="div_user_role_10">
                                                    <?php if (FHtml::isDBSecurityEnabled()) { ?>
                                                        <b><?= FHtml::t('common', 'User Groups') ?> </b><br />
                                                        <?= !FHtml::isTableExisted('auth_group') ? '' : $form->field($model, 'groups_array')->label(false)->checkboxList(FHtml::getApplicationGroupsComboArray()) ?>
                                                        <b><?= FHtml::t('common', 'User Roles') ?> </b><br />
                                                        <?= FHtml::frameworkVersion() == 'framework' || !FHtml::isRoleAdmin() ? '' : $form->field($model, 'rights_array')->label(false)->checkboxList(FHtml::getApplicationRolesComboArray(true), 6) ?>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?php $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
            ?>
            <?= Yii::$app->request->isAjax ? '' : ((FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete, '<div class="row"><div class="col-md-12 col-xs-12">{save}{view}|{cancel} </div></div>')) ?>

        </div>
        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="profile-sidebar col-md-<?= 12 - $size ?> col-xs-12 hidden-print">
                <div class="portlet light">
                    <?= FHtml::showModelPreview($model, ['name'], ['code', 'name', 'email', 'phone:text'], ['image', 'avatar'], ['status', 'username', 'role:label', 'is_online', 'created_date', 'last_login:date']) ?>
                </div>

            </div>
        <?php } ?>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>