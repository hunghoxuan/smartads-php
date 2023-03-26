<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Book".
 */
use common\components\FHtml;
use common\widgets\FActiveForm;
use common\widgets\FFormTable;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormObjectFile;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SETUP';
$moduleTitle = 'Setup';
$moduleKey = 'setup';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole('', $currentAction, $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\book\models\Book */
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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php $form = FActiveForm::begin([
    'id' => 'book-form',
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
<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>
<?= FHtml::render('_menu_right') ?>

<div class="form">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>

                        </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Settings') ?></a>
                        </li>

                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane  active row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Application', 'attributes' => [
                                            'app_name',
                                            'app_description',
                                            'app_website',
                                            'admin_phone'
                                        ]]); ?>
                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Admin Username: <b>admin</b>' ,
                                            'attributes' => [
                                                'admin_password', 'admin_email'
                                            ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Database', 'attributes' => [
                                            'db_name',
                                            'db_host',
                                            'db_database',
                                            'db_username',
                                            'db_password'
                                        ]]); ?>

                                        <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Email', 'attributes' => [
                                            'email_host',
                                            'email_username',
                                            'email_password',
                                            'email_port',
                                            'email_encryption',
                                            'email_useTransport',
                                        ]]); ?>
                                    </div>
                                </div>

                                <?php if ($canEdit) { ?>
                                    <button class="btn btn-primary">SETUP</button>
                                <?php } else {?>
                                    <a href="<?=    FHtml::createUrl('site/index') ?>" class="btn btn-default">Continue</a>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end()  ?>
