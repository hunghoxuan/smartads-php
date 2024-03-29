<?php

/**



 * This is the customized model class for table "Application".
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

$moduleName = 'Application';
$moduleTitle = 'Application';
$moduleKey = 'application';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', $currentAction, $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\Application */
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
    'id' => 'application-form',
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

        <div class="col-md-9">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ? FHtml::showPrintHeader($moduleName) : '' ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>
                            : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?> </span>
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
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Contacts') ?></a>
                        </li>

                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Uploads') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Attributes') ?></a>
                        </li>

                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                            'code' => ['value' => $form->fieldNoLabel($model, 'code')->textInput()],
                                            'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput()],
                                            'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3])],
                                            'keywords' => ['value' => $form->fieldNoLabel($model, 'keywords')->textarea(['rows' => 3])],
                                            'note' => ['value' => $form->fieldNoLabel($model, 'note')->html()],
                                            'modules' => ['value' => $form->fieldNoLabel($model, 'modules')->selectMany(FHtml::getComboArray('application', 'application', 'modules', true, 'id', 'name'))],
                                            'storage_max' => ['value' => $form->fieldNoLabel($model, 'storage_max')->numeric()],
                                            'storage_current' => ['value' => $form->fieldNoLabel($model, 'storage_current')->numeric()],
                                            'copyright' => ['value' => $form->fieldNoLabel($model, 'copyright')->textInput()],
                                            'owner_id' => ['value' => $form->fieldNoLabel($model, 'owner_id')->select(FHtml::getComboArray('@user,id,username', 'user,id,username', 'owner_id', true, 'id', 'name'))],


                                        ]]); ?>


                                        <?php echo FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'Group'), 'form' => $form, 'columns' => 2, 'attributes' => [

                                            'lang' => ['value' => $form->fieldNoLabel($model, 'lang')->select(FHtml::getComboArray('application', 'application', 'lang', true, 'id', 'name'))],
                                            'type' => ['value' => $form->fieldNoLabel($model, 'type')->select(FHtml::getComboArray('application', 'application', 'type', true, 'id', 'name'))],
                                            'status' => ['value' => $form->fieldNoLabel($model, 'status')->select(FHtml::getComboArray('application', 'application', 'status', true, 'id', 'name'))],

                                            'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox()],

                                        ]]); ?>

                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [

                                            'address' => ['value' => $form->fieldNoLabel($model, 'address')->textInput()],
                                            'map' => ['value' => $form->fieldNoLabel($model, 'map')->textInput()],
                                            'website' => ['value' => $form->fieldNoLabel($model, 'website')->textInput()],
                                            'email' => ['value' => $form->fieldNoLabel($model, 'email')->emailInput()],
                                            'phone' => ['value' => $form->fieldNoLabel($model, 'phone')->textInput()],
                                            'fax' => ['value' => $form->fieldNoLabel($model, 'fax')->textInput()],
                                            'chat' => ['value' => $form->fieldNoLabel($model, 'chat')->textInput()],


                                        ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'title' => FHtml::t('common', 'social'), 'form' => $form, 'columns' => 2, 'attributes' => [

                                            'facebook' => ['value' => $form->fieldNoLabel($model, 'facebook')->textInput()],
                                            'twitter' => ['value' => $form->fieldNoLabel($model, 'twitter')->textInput()],
                                            'google' => ['value' => $form->fieldNoLabel($model, 'google')->textInput()],
                                            'youtube' => ['value' => $form->fieldNoLabel($model, 'youtube')->textInput()],


                                        ]]); ?>


                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [

                                            'logo' => ['value' => $form->fieldNoLabel($model, 'logo')->image()],
                                            'terms_of_service' => ['value' => $form->fieldNoLabel($model, 'terms_of_service')->file()],
                                            'profile' => ['value' => $form->fieldNoLabel($model, 'profile')->file()],
                                            'privacy_policy' => ['value' => $form->fieldNoLabel($model, 'privacy_policy')->file()],

                                        ]]); ?>


                                    </div>
                                </div>
                                <div class="tab-pane row" id="tab_1_4">
                                    <div class="col-md-12">
                                        <?php echo FormObjectAttributes::widget(['model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]);
                                        ?>



                                    </div>
                                </div>

                                <!--<div class="tab-pane row" id="tab_1_p">
                                    <div class="col-md-12">
                                                                            </div>
                                </div>
                                -->

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
        <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">
            <div class="portlet light">
                <?= FHtml::showModelPreview($model) ?>
            </div>
        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>