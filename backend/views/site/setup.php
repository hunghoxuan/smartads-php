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

$canEdit = isset($canEdit) ? $canEdit : FHtml::isRoleAdmin();

$ok = \common\components\FConfig::checkDbConnection();

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

<?php $form = FActiveForm::begin([
    'id' => 'book-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => FHtml::EDIT_TYPE_INPUT,
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<?php
if ($ok)
{?>
    <?php
    FHtml::showErrorMessage('System is already installed and running. To reinstall again, please delete current database and run setup.');
    return; ?>
<?php
}
?>

<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>

<?php
echo FHtml::showWizard('fsd', [
    [
        'title' => FHtml::t('common', 'Check System'),
        'icon' => 'glyphicon glyphicon-cloud-download',
        'content' => FHtml::render('info'),
    ],
    [
        'title' => FHtml::t('common', 'Config Database'),
        'icon' => 'glyphicon glyphicon-transfer',
        'content' => FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Database', 'attributes' => [
            //'db_name',
            'db_host',
            'db_database',
            'db_username',
            'db_password'
        ]]),
    ],
    [
        'title' => FHtml::t('common', 'Admin User'),
        'icon' => 'glyphicon glyphicon-cloud-upload',
        'content' => FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Create Admin User' ,
            'attributes' => [
                'admin_username', 'admin_password'
            ]]),
    ],
    [
        'title' => FHtml::t('common', 'Settings'),
        'icon' => 'glyphicon glyphicon-cloud-upload',
        'content' => FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Application', 'attributes' => [
            'app_name',
            'app_description',
            'app_website',
            'admin_phone', 'admin_email'
        ]]) . FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Purchase & License Key', 'attributes' => [
                'purchase_site',
                'client_name',  'client_email',
                'purchase_order',

            ]]),
    ],
    [
        'title' => FHtml::t('common', 'Config Email'),
        'icon' => 'glyphicon glyphicon-transfer',
        'content' => FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Email', 'attributes' => [
            'email_host',
            'email_useTransport',
            'email_username',
            'email_password',
            'email_port',
            'email_encryption',
        ]]),
    ]
], ($canEdit ? ('<button class="btn btn-success uppercase">' . FHtml::t('button', 'Install') . '</button>') : ''));
?>
<?php FActiveForm::end(); ?>

<?= FHtml::t('message', 'For direct question and support, please send email to the author of application') ?>: <b><?= FHtml::getAuthor() ?></b> (<a href="mailto:<?= FHtml::getAuthorEmail()?>"><?= FHtml::getAuthorEmail()?></a>) <br/>
